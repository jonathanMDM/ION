<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class AssetImportService
{
    public function validateAndPreview($file)
    {
        $data = Excel::toArray([], $file)[0];
        
        if (empty($data)) {
            return ['success' => false, 'message' => 'El archivo está vacío'];
        }

        // Verificar encabezados
        $headers = array_shift($data);
        $requiredHeaders = ['custom_id', 'name', 'location', 'category', 'subcategory', 'value', 'status'];
        
        $missingHeaders = array_diff($requiredHeaders, $headers);
        if (!empty($missingHeaders)) {
            return [
                'success' => false,
                'message' => 'Faltan columnas requeridas: ' . implode(', ', $missingHeaders)
            ];
        }

        // Validar y preparar datos
        $validRows = [];
        $errors = [];

        foreach ($data as $index => $row) {
            $rowNumber = $index + 2; // +2 porque array_shift quitó headers y Excel empieza en 1
            $rowData = array_combine($headers, $row);
            
            $validation = $this->validateRow($rowData, $rowNumber);
            
            if ($validation['valid']) {
                $validRows[] = [
                    'row_number' => $rowNumber,
                    'data' => $rowData,
                    'resolved' => $validation['resolved']
                ];
            } else {
                $errors[] = [
                    'row_number' => $rowNumber,
                    'data' => $rowData,
                    'errors' => $validation['errors']
                ];
            }
        }

        return [
            'success' => true,
            'valid_rows' => $validRows,
            'errors' => $errors,
            'total' => count($data),
            'valid_count' => count($validRows),
            'error_count' => count($errors)
        ];
    }

    private function validateRow($row, $rowNumber)
    {
        $errors = [];
        $resolved = [];

        // Validar campos requeridos
        if (empty($row['name'])) {
            $errors[] = 'El nombre es requerido';
        }

        if (empty($row['value']) || !is_numeric($row['value'])) {
            $errors[] = 'El valor debe ser numérico';
        }

        // Resolver ubicación
        if (!empty($row['location'])) {
            $location = Location::where('name', $row['location'])->first();
            if ($location) {
                $resolved['location_id'] = $location->id;
            } else {
                $errors[] = "Ubicación '{$row['location']}' no encontrada";
            }
        } else {
            $errors[] = 'La ubicación es requerida';
        }

        // Resolver categoría y subcategoría
        if (!empty($row['category']) && !empty($row['subcategory'])) {
            $category = Category::where('name', $row['category'])->first();
            if ($category) {
                $subcategory = Subcategory::where('name', $row['subcategory'])
                    ->where('category_id', $category->id)
                    ->first();
                if ($subcategory) {
                    $resolved['subcategory_id'] = $subcategory->id;
                } else {
                    $errors[] = "Subcategoría '{$row['subcategory']}' no encontrada en categoría '{$row['category']}'";
                }
            } else {
                $errors[] = "Categoría '{$row['category']}' no encontrada";
            }
        } else {
            $errors[] = 'Categoría y subcategoría son requeridas';
        }

        // Resolver proveedor (opcional)
        if (!empty($row['supplier'])) {
            $supplier = Supplier::where('name', $row['supplier'])->first();
            if ($supplier) {
                $resolved['supplier_id'] = $supplier->id;
            }
        }

        // Validar estado
        $validStatuses = ['active', 'maintenance', 'decommissioned'];
        if (!empty($row['status']) && !in_array($row['status'], $validStatuses)) {
            $errors[] = "Estado inválido. Debe ser: active, maintenance o decommissioned";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'resolved' => $resolved
        ];
    }

    public function import($validRows)
    {
        $imported = 0;
        $failed = [];

        foreach ($validRows as $item) {
            try {
                $data = $item['data'];
                $resolved = $item['resolved'];

                Asset::create([
                    'custom_id' => $data['custom_id'] ?? null,
                    'name' => $data['name'],
                    'model' => $data['model'] ?? null,
                    'location_id' => $resolved['location_id'],
                    'subcategory_id' => $resolved['subcategory_id'],
                    'supplier_id' => $resolved['supplier_id'] ?? null,
                    'value' => $data['value'],
                    'quantity' => $data['quantity'] ?? 1,
                    'purchase_date' => !empty($data['purchase_date']) ? $data['purchase_date'] : null,
                    'municipality_plate' => $data['municipality_plate'] ?? null,
                    'specifications' => $data['specifications'] ?? null,
                    'status' => $data['status'] ?? 'active',
                ]);

                $imported++;
            } catch (\Exception $e) {
                $failed[] = [
                    'row' => $item['row_number'],
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'imported' => $imported,
            'failed' => $failed
        ];
    }
}
