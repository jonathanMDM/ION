<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Category;
use App\Models\Subcategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetsImport implements ToModel, WithHeadingRow
{
    protected $companyId;
    protected $customFields;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
        $this->customFields = \App\Models\CustomField::where('company_id', $companyId)->pluck('name')->toArray();
    }

    public function model(array $row)
    {
        // Find or create Location
        $location = Location::firstOrCreate(
            ['name' => $row['location'], 'company_id' => $this->companyId],
            ['company_id' => $this->companyId]
        );

        // Find or create Category
        $category = Category::firstOrCreate(
            ['name' => $row['category'], 'company_id' => $this->companyId],
            ['company_id' => $this->companyId]
        );

        // Find or create Subcategory
        $subcategory = Subcategory::firstOrCreate([
            'name' => $row['subcategory'],
            'category_id' => $category->id,
            'company_id' => $this->companyId
        ]);

        // Parse purchase date - handle both Excel dates and text dates
        $purchaseDate = null;
        if (isset($row['purchase_date']) && !empty($row['purchase_date'])) {
            // Check if it's a numeric Excel date
            if (is_numeric($row['purchase_date'])) {
                $purchaseDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']);
            } else {
                // Try to parse as text date
                try {
                    $purchaseDate = new \DateTime($row['purchase_date']);
                } catch (\Exception $e) {
                    $purchaseDate = null;
                }
            }
        }

        // Process custom fields
        $customAttributes = [];
        foreach ($this->customFields as $fieldName) {
            if (isset($row[$fieldName])) {
                $customAttributes[$fieldName] = $row[$fieldName];
            }
        }

        return new Asset([
            'company_id' => $this->companyId,
            'name' => $row['name'],
            'location_id' => $location->id,
            'subcategory_id' => $subcategory->id,
            'value' => $row['value'] ?? 0,
            'purchase_date' => $purchaseDate,
            'status' => $row['status'] ?? 'active',
            'specifications' => $row['specifications'] ?? null,
            'municipality_plate' => $row['municipality_plate'] ?? null,
            'custom_id' => $row['custom_id'] ?? null,
            'quantity' => $row['quantity'] ?? 1,
            'custom_attributes' => !empty($customAttributes) ? $customAttributes : null,
        ]);
    }
}
