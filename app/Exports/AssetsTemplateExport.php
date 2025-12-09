<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\CustomField;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FieldHelper;

class AssetsTemplateExport implements WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        // Standard required fields
        $headings = [
            'name',
            'location',
            'category',
            'subcategory',
            'value',
            'status',
            'quantity',
            'purchase_date',
            'specifications',
            'custom_id',
        ];

        // Optional standard fields based on visibility
        if (FieldHelper::isVisible('municipality_plate')) {
            $headings[] = 'municipality_plate';
        }

        // Custom fields
        $customFields = CustomField::where('company_id', Auth::user()->company_id)->get();
        
        foreach ($customFields as $field) {
            if (FieldHelper::isVisible($field->name)) {
                $headings[] = $field->name;
            }
        }

        return $headings;
    }
}
