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
            'file' => 'required|mimes:csv,txt',
        ]);

        $companyId = \Illuminate\Support\Facades\Auth::user()->company_id;
        $file = $request->file('file');
        
        \Log::info('Import started', ['company_id' => $companyId, 'filename' => $file->getClientOriginalName()]);
        
        try {
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
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                \Log::info("Processing row $rowNumber", ['data' => $row]);
                
                try {
                    // Validate required fields
                    if (empty($row[0]) || empty($row[1])) {
                        $error = "Fila $rowNumber: custom_id y name son requeridos";
                        $errors[] = $error;
                        \Log::warning($error);
                        continue;
                    }
                    
                    // Check if custom_id already exists and make it unique if needed
                    $customId = trim($row[0]);
                    $originalCustomId = $customId;
                    $counter = 1;
                    while (\App\Models\Asset::where('custom_id', $customId)->where('company_id', $companyId)->exists()) {
                        $customId = $originalCustomId . '-' . $counter;
                        $counter++;
                    }
                    
                    if ($customId !== $originalCustomId) {
                        \Log::info("Custom ID duplicado, cambiado de '$originalCustomId' a '$customId'");
                    }
                    
                    // Map CSV columns to asset fields
                    $assetData = [
                        'custom_id' => $customId,
                        'name' => trim($row[1]),
                        'specifications' => $row[2] ?? '',
                        'quantity' => !empty($row[3]) ? (int)$row[3] : 1,
                        'value' => !empty($row[4]) ? (float)$row[4] : 0,
                        'purchase_date' => !empty($row[5]) ? $row[5] : null,
                        'status' => !empty($row[6]) ? $row[6] : 'active',
                        'municipality_plate' => $row[11] ?? null,
                        'notes' => $row[12] ?? null,
                        'company_id' => $companyId,
                    ];
                    
                    // Find or create location
                    if (!empty($row[7])) {
                        $location = \App\Models\Location::firstOrCreate(
                            ['name' => trim($row[7]), 'company_id' => $companyId],
                            ['description' => 'Creado automáticamente desde importación']
                        );
                        $assetData['location_id'] = $location->id;
                        \Log::info("Location processed", ['location' => $location->name, 'id' => $location->id]);
                    }
                    
                    // Find or create category and subcategory
                    if (!empty($row[8]) && !empty($row[9])) {
                        $category = \App\Models\Category::firstOrCreate(
                            ['name' => trim($row[8]), 'company_id' => $companyId]
                        );
                        
                        $subcategory = \App\Models\Subcategory::firstOrCreate(
                            ['name' => trim($row[9]), 'category_id' => $category->id, 'company_id' => $companyId]
                        );
                        $assetData['subcategory_id'] = $subcategory->id;
                        \Log::info("Category/Subcategory processed", ['category' => $category->name, 'subcategory' => $subcategory->name]);
                    }
                    
                    // Find or create supplier
                    if (!empty($row[10])) {
                        $supplier = \App\Models\Supplier::firstOrCreate(
                            ['name' => trim($row[10]), 'company_id' => $companyId],
                            ['email' => '', 'phone' => '']
                        );
                        $assetData['supplier_id'] = $supplier->id;
                        \Log::info("Supplier processed", ['supplier' => $supplier->name]);
                    }
                    
                    $asset = \App\Models\Asset::create($assetData);
                    $imported++;
                    \Log::info("Asset created", ['asset_id' => $asset->id, 'name' => $asset->name]);
                    
                } catch (\Exception $e) {
                    $error = "Fila $rowNumber: " . $e->getMessage();
                    $errors[] = $error;
                    \Log::error($error, ['exception' => $e]);
                }
            }
            
            fclose($handle);
            
            \Log::info('Import completed', ['imported' => $imported, 'errors' => count($errors)]);
            
            $message = "Se importaron exitosamente $imported activos.";
            
            if (count($errors) > 0) {
                $message .= " Errores encontrados: " . count($errors) . ". ";
                $message .= implode(' | ', array_slice($errors, 0, 5));
                return redirect()->route('assets.index')->with('warning', $message);
            }
            
            return redirect()->route('assets.index')->with('success', $message);
                
        } catch (\Exception $e) {
            \Log::error('Import failed', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filename = 'plantilla_activos.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers in Spanish with descriptions (using semicolon as delimiter for Excel)
            fputcsv($file, [
                'ID Único (Ej: ACT-001)',
                'Nombre del Activo',
                'Especificaciones',
                'Cantidad',
                'Valor ($)',
                'Fecha de Compra (AAAA-MM-DD)',
                'Estado (active/maintenance/decommissioned)',
                'Ubicación',
                'Categoría',
                'Subcategoría',
                'Proveedor',
                'Placa Municipio',
                'Notas'
            ], ';');

            // Example row 1 - Computer
            fputcsv($file, [
                'ACT-001',
                'Laptop Dell Latitude 5420',
                'Intel Core i5-1135G7, 16GB RAM, 512GB SSD, Windows 11 Pro',
                '1',
                '1250000',
                '2024-01-15',
                'active',
                'Oficina Principal',
                'Tecnología',
                'Computadoras',
                'Dell Colombia',
                '',
                'Asignada al departamento de IT'
            ], ';');

            // Example row 2 - Furniture
            fputcsv($file, [
                'ACT-002',
                'Escritorio Ejecutivo',
                'Madera MDF, 1.60m x 0.80m, color nogal',
                '5',
                '450000',
                '2024-02-20',
                'active',
                'Sala de Juntas',
                'Mobiliario',
                'Escritorios',
                'Muebles & Diseño',
                '',
                'Para sala de reuniones'
            ], ';');

            // Example row 3 - Vehicle
            fputcsv($file, [
                'ACT-003',
                'Camioneta Toyota Hilux',
                '4x4, Diesel, 2.8L, Doble Cabina, Blanca',
                '1',
                '95000000',
                '2023-06-10',
                'active',
                'Almacén General',
                'Vehículos',
                'Camionetas',
                'Toyota Colombia',
                'ABC-123',
                'Vehículo de carga'
            ], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
