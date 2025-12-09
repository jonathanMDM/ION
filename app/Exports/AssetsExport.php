<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;
    protected $customFields;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->customFields = \App\Models\CustomField::where('company_id', \Illuminate\Support\Facades\Auth::user()->company_id)->get();
    }

    public function query()
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier']);

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['location_id'])) {
            $query->where('location_id', $this->filters['location_id']);
        }

        if (isset($this->filters['category_id'])) {
            $query->whereHas('subcategory', function($q) {
                $q->where('category_id', $this->filters['category_id']);
            });
        }

        if (isset($this->filters['subcategory_id'])) {
            $query->where('subcategory_id', $this->filters['subcategory_id']);
        }

        if (isset($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
        }

        if (isset($this->filters['date_from'])) {
            $query->where('purchase_date', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->where('purchase_date', '<=', $this->filters['date_to']);
        }

        if (isset($this->filters['model'])) {
            $query->where('model', 'like', '%' . $this->filters['model'] . '%');
        }

        if (isset($this->filters['custom_id'])) {
            $query->where('custom_id', 'like', '%' . $this->filters['custom_id'] . '%');
        }

        if (isset($this->filters['municipality_plate'])) {
            $query->where('municipality_plate', 'like', '%' . $this->filters['municipality_plate'] . '%');
        }

        if (isset($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('custom_id', 'like', '%' . $search . '%')
                  ->orWhere('municipality_plate', 'like', '%' . $search . '%')
                  ->orWhere('specifications', 'like', '%' . $search . '%');
            });
        }

        // Filter by custom fields
        foreach ($this->customFields as $field) {
            $filterKey = 'custom_' . $field->name;
            if (isset($this->filters[$filterKey])) {
                $query->whereRaw("JSON_EXTRACT(custom_attributes, '$.{$field->name}') LIKE ?", ['%' . $this->filters[$filterKey] . '%']);
            }
        }

        return $query;
    }

    public function headings(): array
    {
        $headings = [
            'ID Único',
            'Nombre',
            'Modelo',
        ];

        if (\App\Helpers\FieldHelper::isVisible('municipality_plate')) {
            $headings[] = 'Placa Municipio';
        }

        foreach ($this->customFields as $field) {
            if (\App\Helpers\FieldHelper::isVisible($field->name)) {
                $headings[] = $field->label;
            }
        }

        $headings = array_merge($headings, [
            'Ubicación',
            'Categoría',
            'Subcategoría',
            'Proveedor',
            'Estado',
            'Cantidad',
            'Valor',
            'Fecha de Compra',
            'Especificaciones',
        ]);

        return $headings;
    }

    public function map($asset): array
    {
        $data = [
            $asset->custom_id,
            $asset->name,
            $asset->model ?? '',
        ];

        if (\App\Helpers\FieldHelper::isVisible('municipality_plate')) {
            $data[] = $asset->municipality_plate;
        }

        foreach ($this->customFields as $field) {
            if (\App\Helpers\FieldHelper::isVisible($field->name)) {
                $data[] = $asset->custom_attributes[$field->name] ?? '';
            }
        }

        $data = array_merge($data, [
            $asset->location->name,
            $asset->subcategory->category->name,
            $asset->subcategory->name,
            $asset->supplier->name ?? 'N/A',
            $asset->status == 'active' ? 'Activo' : ($asset->status == 'maintenance' ? 'Mantenimiento' : 'Dado de Baja'),
            $asset->quantity,
            $asset->value,
            $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '',
            $asset->specifications,
        ]);

        return $data;
    }
}
