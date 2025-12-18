<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\AssetsImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AssetsTemplateExport;

class ImportController extends Controller
{
    public function create()
    {
        return view('imports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);

        $companyId = \Illuminate\Support\Facades\Auth::user()->company_id;
        $file = $request->file('file');
        
        \Log::info('Import started', ['company_id' => $companyId, 'filename' => $file->getClientOriginalName()]);
        
        try {
            $extension = $file->getClientOriginalExtension();
            
            // Handle Excel files (.xlsx, .xls)
            if (in_array($extension, ['xlsx', 'xls'])) {
                return $this->importExcel($file, $companyId);
            }
            
            // Handle CSV/TXT files
            $handle = fopen($file->getRealPath(), 'r');
            
            if (!$handle) {
                \Log::error('Could not open file');
                return redirect()->back()->with('error', 'No se pudo abrir el archivo.');
            }
            
            // Detect delimiter (semicolon, comma, or tab)
            $firstLine = fgets($handle);
            rewind($handle);
            
            $delimiter = ',';
            // Check for semicolon first (Excel Spanish format)
            if (strpos($firstLine, ';') !== false) {
                $delimiter = ';';
                \Log::info('Detected semicolon-separated file');
            }
            // Then check for tab
            elseif (strpos($firstLine, "\t") !== false && strpos($firstLine, ',') === false) {
                $delimiter = "\t";
                \Log::info('Detected tab-separated file');
            } else {
                \Log::info('Detected comma-separated file');
            }
            
            // Skip header row
            $header = fgetcsv($handle, 0, $delimiter);
            \Log::info('CSV Headers', ['headers' => $header, 'delimiter' => $delimiter === "\t" ? 'TAB' : ($delimiter === ';' ? 'SEMICOLON' : 'COMMA')]);
            
            $imported = 0;
            $errors = [];
            $rowNumber = 1; // Start at 1 (header is 0)
            
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNumber++;
                
                \Log::info("Raw row $rowNumber", ['data' => $row, 'count' => count($row)]);
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    \Log::warning("Skipping empty row $rowNumber");
                    continue;
                }
                
                \Log::info("Processing row $rowNumber", ['custom_id' => $row[0] ?? 'N/A', 'name' => $row[1] ?? 'N/A']);
                
                try {
                    $result = $this->processRow($row, $rowNumber, $companyId);
                    if ($result['success']) {
                        $imported++;
                    } else {
                        $errors[] = $result['error'];
                    }
                } catch (\Exception $e) {
                    $error = "Fila $rowNumber: " . $e->getMessage();
                    $errors[] = $error;
                    \Log::error($error, ['exception' => $e]);
                }
            }
            
            fclose($handle);
            
            \Log::info('Import completed', [
                'total_rows' => $rowNumber - 1, 
                'imported' => $imported, 
                'errors' => count($errors)
            ]);
            
            $message = "Se procesaron " . ($rowNumber - 1) . " filas. Se importaron exitosamente $imported activos.";
            
            if (count($errors) > 0) {
                $message .= " Errores encontrados: " . count($errors) . ". ";
                $message .= implode(' | ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " (y " . (count($errors) - 5) . " más...)";
                }
                return redirect()->route('assets.index')->with('warning', $message);
            }
            
            return redirect()->route('assets.index')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Import failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
    
    private function importExcel($file, $companyId)
    {
        try {
            // Increase time limit for large imports
            set_time_limit(300); // 5 minutes
            ini_set('max_execution_time', 300);
            
            // Use PhpSpreadsheet to read Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            \Log::info('Excel file loaded', ['total_rows' => count($rows)]);
            
            // Skip header row
            $header = array_shift($rows);
            \Log::info('Excel Headers', ['headers' => $header]);
            
            // Pre-load existing custom_ids for this company to avoid repeated queries
            $existingCustomIds = \App\Models\Asset::where('company_id', $companyId)
                ->pluck('custom_id')
                ->flip()
                ->toArray();
            
            // Pre-load existing locations, categories, subcategories, suppliers
            $existingLocations = \App\Models\Location::where('company_id', $companyId)
                ->pluck('id', 'name')
                ->toArray();
            
            $existingCategories = \App\Models\Category::where('company_id', $companyId)
                ->pluck('id', 'name')
                ->toArray();
            
            $existingSuppliers = \App\Models\Supplier::where('company_id', $companyId)
                ->pluck('id', 'name')
                ->toArray();
            
            $imported = 0;
            $errors = [];
            $rowNumber = 1;
            
            foreach ($rows as $row) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Log every 100 rows to avoid too much logging
                if ($rowNumber % 100 == 0) {
                    \Log::info("Processing row $rowNumber");
                }
                
                try {
                    $result = $this->processRow(
                        $row, 
                        $rowNumber, 
                        $companyId, 
                        $existingCustomIds,
                        $existingLocations,
                        $existingCategories,
                        $existingSuppliers
                    );
                    
                    if ($result['success']) {
                        $imported++;
                        // Add newly created custom_id to the cache
                        if (isset($result['custom_id'])) {
                            $existingCustomIds[$result['custom_id']] = true;
                        }
                    } else {
                        $errors[] = $result['error'];
                    }
                } catch (\Exception $e) {
                    $error = "Fila $rowNumber: " . $e->getMessage();
                    $errors[] = $error;
                    \Log::error($error);
                }
            }
            
            \Log::info('Excel import completed', [
                'total_rows' => $rowNumber - 1, 
                'imported' => $imported, 
                'errors' => count($errors)
            ]);
            
            $message = "Se procesaron " . ($rowNumber - 1) . " filas. Se importaron exitosamente $imported activos.";
            
            if (count($errors) > 0) {
                $message .= " Errores encontrados: " . count($errors) . ". ";
                $message .= implode(' | ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " (y " . (count($errors) - 5) . " más...)";
                }
                return redirect()->route('assets.index')->with('warning', $message);
            }
            
            return redirect()->route('assets.index')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Excel import failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al importar Excel: ' . $e->getMessage());
        }
    }
    
    private function processRow($row, $rowNumber, $companyId, &$existingCustomIds, &$existingLocations, &$existingCategories, &$existingSuppliers)
    {
        // Validate required fields
        if (empty($row[0]) || empty($row[1])) {
            return [
                'success' => false,
                'error' => "Fila $rowNumber: ID y Nombre son requeridos"
            ];
        }
                    
        // Check if custom_id already exists using cached data
        $customId = trim($row[0]);
        $originalCustomId = $customId;
        $counter = 1;
        while (isset($existingCustomIds[$customId])) {
            $customId = $originalCustomId . '-' . $counter;
            $counter++;
        }
        
        if ($customId !== $originalCustomId) {
            \Log::info("Custom ID duplicado, cambiado de '$originalCustomId' a '$customId'");
        }
        
        // Parse purchase date with flexible format support
        $purchaseDate = null;
        if (!empty($row[5])) {
            try {
                // Try multiple date formats
                $dateString = trim($row[5]);
                
                // Try dd/mm/yy format (Excel default)
                if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2})$/', $dateString, $matches)) {
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $year = '20' . $matches[3]; // Assume 20xx
                    $purchaseDate = "$year-$month-$day";
                }
                // Try dd/mm/yyyy format
                elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $year = $matches[3];
                    $purchaseDate = "$year-$month-$day";
                }
                // Try yyyy-mm-dd format (standard)
                elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                    $purchaseDate = $dateString;
                }
                else {
                    // Try Carbon parse as last resort
                    $purchaseDate = \Carbon\Carbon::parse($dateString)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                \Log::warning("Could not parse date '$row[5]' for row $rowNumber, using null");
                $purchaseDate = null;
            }
        }
        
        // Map status from Excel to valid database values
        // Valid values: 'active', 'decommissioned', 'maintenance'
        $statusMapping = [
            'OPERACION' => 'active',
            'OPERACIÓN' => 'active',
            'EN USO' => 'active',
            'DISPONIBLE' => 'active',
            'ACTIVO' => 'active',
            'ACTIVE' => 'active',
            'MANTENIMIENTO' => 'maintenance',
            'MAINTENANCE' => 'maintenance',
            'BAJA' => 'decommissioned',
            'RETIRADO' => 'decommissioned',
            'DECOMMISSIONED' => 'decommissioned',
            'PERDIDO' => 'decommissioned',
            'DAÑADO' => 'decommissioned',
            'DAMAGED' => 'decommissioned',
        ];
        
        $rawStatus = !empty($row[6]) ? strtoupper(trim($row[6])) : 'DISPONIBLE';
        $mappedStatus = $statusMapping[$rawStatus] ?? 'active';
        
        // Debug log for first few rows
        if ($rowNumber <= 5) {
            \Log::info("Status mapping debug", [
                'row' => $rowNumber,
                'raw_status' => $row[6] ?? 'empty',
                'uppercase_status' => $rawStatus,
                'mapped_status' => $mappedStatus
            ]);
        }
        
        // Map columns to asset fields
        $assetData = [
            'custom_id' => $customId,
            'name' => trim($row[1]),
            'specifications' => $row[2] ?? '',
            'quantity' => !empty($row[3]) ? (int)$row[3] : 1,
            'value' => !empty($row[4]) ? (float)$row[4] : 0,
            'purchase_date' => $purchaseDate,
            'status' => $mappedStatus,
            'municipality_plate' => $row[11] ?? null,
            'notes' => $row[12] ?? null,
            'company_id' => $companyId,
        ];
        
        // Find or create location using cache
        if (!empty($row[7])) {
            $locationName = trim($row[7]);
            if (isset($existingLocations[$locationName])) {
                $assetData['location_id'] = $existingLocations[$locationName];
            } else {
                $location = \App\Models\Location::create([
                    'name' => $locationName,
                    'company_id' => $companyId,
                    'address' => ''
                ]);
                $assetData['location_id'] = $location->id;
                $existingLocations[$locationName] = $location->id;
            }
        }
        
        // Find or create category and subcategory using cache
        if (!empty($row[8]) && !empty($row[9])) {
            $categoryName = trim($row[8]);
            
            if (isset($existingCategories[$categoryName])) {
                $categoryId = $existingCategories[$categoryName];
            } else {
                $category = \App\Models\Category::create([
                    'name' => $categoryName,
                    'company_id' => $companyId
                ]);
                $categoryId = $category->id;
                $existingCategories[$categoryName] = $categoryId;
            }
            
            // For subcategories, we need a different cache structure
            $subcategoryName = trim($row[9]);
            $subcategory = \App\Models\Subcategory::firstOrCreate(
                ['name' => $subcategoryName, 'category_id' => $categoryId, 'company_id' => $companyId]
            );
            $assetData['subcategory_id'] = $subcategory->id;
        }
        
        // Find or create supplier using cache
        if (!empty($row[10])) {
            $supplierName = trim($row[10]);
            if (isset($existingSuppliers[$supplierName])) {
                $assetData['supplier_id'] = $existingSuppliers[$supplierName];
            } else {
                $supplier = \App\Models\Supplier::create([
                    'name' => $supplierName,
                    'company_id' => $companyId,
                    'email' => '',
                    'phone' => ''
                ]);
                $assetData['supplier_id'] = $supplier->id;
                $existingSuppliers[$supplierName] = $supplier->id;
            }
        }
        
        $asset = \App\Models\Asset::create($assetData);
        \Log::info("Asset created", ['asset_id' => $asset->id, 'name' => $asset->name]);
        
        return [
            'success' => true,
            'custom_id' => $customId
        ];
    }
    
    public function downloadTemplate()
    {
        try {
            // Create new Spreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('ION Inventory System')
                ->setTitle('Plantilla de Importación de Activos')
                ->setSubject('Plantilla para importar activos')
                ->setDescription('Plantilla para importación masiva de activos al sistema ION');
            
            // Headers in Spanish - conditional based on field visibility
            $headers = [
                'ID Unico',
                'Nombre',
                'Especificaciones',
                'Cantidad',
                'Valor',
                'Fecha de Compra',
                'Estado',
                'Ubicacion',
                'Categoria',
                'Subcategoria',
                'Proveedor',
            ];
            
            // Add Placa Municipio only if visible for the company
            if (\App\Helpers\FieldHelper::isVisible('municipality_plate')) {
                $headers[] = 'Placa Municipio';
            }
            
            $headers[] = 'Notas';
            
            // Set headers
            $sheet->fromArray($headers, NULL, 'A1');
            
            // Style headers
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ];
            
            // Apply header style to all header columns dynamically
            $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
            $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);
            
            // Example data - conditional based on field visibility
            if (\App\Helpers\FieldHelper::isVisible('municipality_plate')) {
                $examples = [
                    ['ACT-001', 'Laptop Dell Latitude 5420', 'Intel Core i5-1135G7, 16GB RAM, 512GB SSD', 1, 1250000, '2024-01-15', 'active', 'Oficina Principal', 'Tecnologia', 'Computadoras', 'Dell Colombia', 'N/A', 'Asignada al departamento de IT'],
                    ['ACT-002', 'Escritorio Ejecutivo', 'Madera MDF, 1.60m x 0.80m, color nogal', 5, 450000, '2024-02-20', 'active', 'Sala de Juntas', 'Mobiliario', 'Escritorios', 'Muebles y Diseno', 'N/A', 'Para sala de reuniones'],
                    ['ACT-003', 'Camioneta Toyota Hilux', '4x4, Diesel, 2.8L, Doble Cabina, Blanca', 1, 95000000, '2023-06-10', 'active', 'Almacen General', 'Vehiculos', 'Camionetas', 'Toyota Colombia', 'ABC-123', 'Vehiculo de carga']
                ];
            } else {
                $examples = [
                    ['ACT-001', 'Laptop Dell Latitude 5420', 'Intel Core i5-1135G7, 16GB RAM, 512GB SSD', 1, 1250000, '2024-01-15', 'active', 'Oficina Principal', 'Tecnologia', 'Computadoras', 'Dell Colombia', 'Asignada al departamento de IT'],
                    ['ACT-002', 'Escritorio Ejecutivo', 'Madera MDF, 1.60m x 0.80m, color nogal', 5, 450000, '2024-02-20', 'active', 'Sala de Juntas', 'Mobiliario', 'Escritorios', 'Muebles y Diseno', 'Para sala de reuniones'],
                    ['ACT-003', 'Camioneta Toyota Hilux', '4x4, Diesel, 2.8L, Doble Cabina, Blanca', 1, 95000000, '2023-06-10', 'active', 'Almacen General', 'Vehiculos', 'Camionetas', 'Toyota Colombia', 'Vehiculo de carga']
                ];
            }
            
            $sheet->fromArray($examples, NULL, 'A2');
            
            // Auto-size columns dynamically
            for ($col = 1; $col <= count($headers); $col++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }
            
            // Set row height for header
            $sheet->getRowDimension(1)->setRowHeight(25);
            
            // Add borders to data area (dynamically based on column count)
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ]
            ];
            $sheet->getStyle('A1:' . $lastColumn . '4')->applyFromArray($styleArray);
            
            // Create Excel file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            // Set headers for download
            $filename = 'plantilla_activos.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Save to output
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            \Log::error('Error generating template', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al generar la plantilla: ' . $e->getMessage());
        }
    }
}
