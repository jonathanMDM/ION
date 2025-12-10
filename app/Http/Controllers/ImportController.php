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
        
        try {
            $handle = fopen($file->getRealPath(), 'r');
            
            // Skip header row
            $header = fgetcsv($handle);
            
            $imported = 0;
            $errors = [];
            
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    // Map CSV columns to asset fields
                    $assetData = [
                        'custom_id' => $row[0] ?? null,
                        'name' => $row[1] ?? null,
                        'specifications' => $row[2] ?? null,
                        'quantity' => $row[3] ?? 1,
                        'value' => $row[4] ?? 0,
                        'purchase_date' => $row[5] ?? null,
                        'status' => $row[6] ?? 'active',
                        'municipality_plate' => $row[11] ?? null,
                        'notes' => $row[12] ?? null,
                        'company_id' => $companyId,
                    ];
                    
                    // Find or create location
                    if (!empty($row[7])) {
                        $location = \App\Models\Location::firstOrCreate(
                            ['name' => $row[7], 'company_id' => $companyId],
                            ['description' => 'Creado automáticamente']
                        );
                        $assetData['location_id'] = $location->id;
                    }
                    
                    // Find or create category and subcategory
                    if (!empty($row[8]) && !empty($row[9])) {
                        $category = \App\Models\Category::firstOrCreate(
                            ['name' => $row[8], 'company_id' => $companyId]
                        );
                        
                        $subcategory = \App\Models\Subcategory::firstOrCreate(
                            ['name' => $row[9], 'category_id' => $category->id, 'company_id' => $companyId]
                        );
                        $assetData['subcategory_id'] = $subcategory->id;
                    }
                    
                    // Find or create supplier
                    if (!empty($row[10])) {
                        $supplier = \App\Models\Supplier::firstOrCreate(
                            ['name' => $row[10], 'company_id' => $companyId],
                            ['email' => '', 'phone' => '']
                        );
                        $assetData['supplier_id'] = $supplier->id;
                    }
                    
                    \App\Models\Asset::create($assetData);
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Error en fila " . ($imported + 1) . ": " . $e->getMessage();
                }
            }
            
            fclose($handle);
            
            if (count($errors) > 0) {
                return redirect()->route('assets.index')
                    ->with('warning', "Importados: $imported activos. Errores: " . implode(', ', array_slice($errors, 0, 3)));
            }
            
            return redirect()->route('assets.index')
                ->with('success', "Se importaron exitosamente $imported activos.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filename = 'plantilla_activos.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'custom_id',
                'name',
                'specifications',
                'quantity',
                'value',
                'purchase_date',
                'status',
                'location_name',
                'category_name',
                'subcategory_name',
                'supplier_name',
                'municipality_plate',
                'notes'
            ]);

            // Example row
            fputcsv($file, [
                'ACT-001',
                'Laptop Dell',
                'Core i5, 8GB RAM',
                '1',
                '800.00',
                date('Y-m-d'),
                'active',
                'Oficina Principal',
                'Tecnología',
                'Computadoras',
                'Dell Inc',
                'MUN-001',
                'Ejemplo de activo'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
