<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use App\Models\Asset;
use App\Models\Location;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Supplier;

class ProcessExcelImport implements ShouldQueue
{
    use Queueable;

    public $timeout = 600; // 10 minutes timeout
    public $tries = 1;

    protected $filePath;
    protected $companyId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $companyId, $userId)
    {
        $this->filePath = $filePath;
        $this->companyId = $companyId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            \Log::info('Starting background Excel import', [
                'file' => $this->filePath,
                'company_id' => $this->companyId
            ]);

            // Load Excel file from storage
            $fullPath = storage_path('app/' . $this->filePath);
            
            if (!file_exists($fullPath)) {
                \Log::error('Excel file not found', ['path' => $fullPath]);
                return;
            }

            // Use PhpSpreadsheet to read Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            // Pre-load existing data
            $existingCustomIds = Asset::where('company_id', $this->companyId)
                ->pluck('custom_id')
                ->flip()
                ->toArray();
            
            $existingLocations = Location::where('company_id', $this->companyId)
                ->pluck('id', 'name')
                ->toArray();
            
            $existingCategories = Category::where('company_id', $this->companyId)
                ->pluck('id', 'name')
                ->toArray();
            
            $existingSuppliers = Supplier::where('company_id', $this->companyId)
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
                
                // Log progress every 100 rows
                if ($rowNumber % 100 == 0) {
                    \Log::info("Background import progress: row $rowNumber");
                }
                
                try {
                    $result = $this->processRow(
                        $row,
                        $rowNumber,
                        $existingCustomIds,
                        $existingLocations,
                        $existingCategories,
                        $existingSuppliers
                    );
                    
                    if ($result['success']) {
                        $imported++;
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
            
            \Log::info('Background Excel import completed', [
                'total_rows' => $rowNumber - 1,
                'imported' => $imported,
                'errors' => count($errors)
            ]);
            
            // Clean up the temporary file
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
            }
            
            // Create notification for user
            \App\Models\UserNotification::create([
                'user_id' => $this->userId,
                'type' => 'import_completed',
                'title' => 'Importación Completada',
                'message' => "Se importaron $imported de " . ($rowNumber - 1) . " activos. " . 
                            (count($errors) > 0 ? count($errors) . " errores encontrados." : ""),
                'data' => json_encode([
                    'imported' => $imported,
                    'total' => $rowNumber - 1,
                    'errors' => count($errors)
                ])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Background import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function processRow($row, $rowNumber, &$existingCustomIds, &$existingLocations, &$existingCategories, &$existingSuppliers)
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
        
        // Parse purchase date
        $purchaseDate = null;
        if (!empty($row[5])) {
            try {
                if (is_numeric($row[5])) {
                    $purchaseDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])->format('Y-m-d');
                } else {
                    $purchaseDate = date('Y-m-d', strtotime($row[5]));
                }
            } catch (\Exception $e) {
                $purchaseDate = null;
            }
        }
        
        // Map status
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
            'company_id' => $this->companyId,
        ];
        
        // Find or create location using cache
        if (!empty($row[7])) {
            $locationName = trim($row[7]);
            if (isset($existingLocations[$locationName])) {
                $assetData['location_id'] = $existingLocations[$locationName];
            } else {
                $location = Location::create([
                    'name' => $locationName,
                    'company_id' => $this->companyId,
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
                $category = Category::create([
                    'name' => $categoryName,
                    'company_id' => $this->companyId
                ]);
                $categoryId = $category->id;
                $existingCategories[$categoryName] = $categoryId;
            }
            
            $subcategoryName = trim($row[9]);
            $subcategory = Subcategory::firstOrCreate(
                ['name' => $subcategoryName, 'category_id' => $categoryId, 'company_id' => $this->companyId]
            );
            $assetData['subcategory_id'] = $subcategory->id;
        }
        
        // Find or create supplier using cache
        if (!empty($row[10])) {
            $supplierName = trim($row[10]);
            if (isset($existingSuppliers[$supplierName])) {
                $assetData['supplier_id'] = $existingSuppliers[$supplierName];
            } else {
                $supplier = Supplier::create([
                    'name' => $supplierName,
                    'company_id' => $this->companyId,
                    'email' => '',
                    'phone' => ''
                ]);
                $assetData['supplier_id'] = $supplier->id;
                $existingSuppliers[$supplierName] = $supplier->id;
            }
        }
        
        Asset::create($assetData);
        
        return [
            'success' => true,
            'custom_id' => $customId
        ];
    }
}
