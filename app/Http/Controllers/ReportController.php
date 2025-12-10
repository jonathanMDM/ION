<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->where('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('custom_id')) {
            $query->where('custom_id', 'like', '%' . $request->custom_id . '%');
        }

        if ($request->filled('municipality_plate')) {
            $query->where('municipality_plate', 'like', '%' . $request->municipality_plate . '%');
        }

        // Filter by custom fields
        $customFields = \App\Models\CustomField::where('company_id', \Auth::user()->company_id)->get();
        foreach ($customFields as $field) {
            $filterKey = 'custom_' . $field->name;
            if ($request->filled($filterKey)) {
                $query->whereRaw("JSON_EXTRACT(custom_attributes, '$.{$field->name}') LIKE ?", ['%' . $request->$filterKey . '%']);
            }
        }

        // General search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('custom_id', 'like', '%' . $search . '%')
                  ->orWhere('municipality_plate', 'like', '%' . $search . '%')
                  ->orWhere('specifications', 'like', '%' . $search . '%');
            });
        }

        $assets = $query->get();

        // Calculate statistics
        $stats = [
            'total_assets' => $assets->sum('quantity'),
            'total_value' => $assets->sum('value'),
            'active' => $assets->where('status', 'active')->count(),
            'maintenance' => $assets->where('status', 'maintenance')->count(),
            'decommissioned' => $assets->where('status', 'decommissioned')->count(),
            'with_plate' => $assets->whereNotNull('municipality_plate')->where('municipality_plate', '!=', '')->count(),
        ];

        $locations = Location::all();
        $categories = Category::all();
        $subcategories = \App\Models\Subcategory::with('category')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('reports.index', compact('assets', 'stats', 'locations', 'categories', 'subcategories', 'suppliers'));
    }

    public function exportPdf(Request $request)
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier']);

        // Apply same filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->where('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('custom_id')) {
            $query->where('custom_id', 'like', '%' . $request->custom_id . '%');
        }

        if ($request->filled('municipality_plate')) {
            $query->where('municipality_plate', 'like', '%' . $request->municipality_plate . '%');
        }

        // Filter by custom fields
        $customFields = \App\Models\CustomField::where('company_id', \Auth::user()->company_id)->get();
        foreach ($customFields as $field) {
            $filterKey = 'custom_' . $field->name;
            if ($request->filled($filterKey)) {
                $query->whereRaw("JSON_EXTRACT(custom_attributes, '$.{$field->name}') LIKE ?", ['%' . $request->$filterKey . '%']);
            }
        }

        // General search across multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('custom_id', 'like', '%' . $search . '%')
                  ->orWhere('municipality_plate', 'like', '%' . $search . '%')
                  ->orWhere('specifications', 'like', '%' . $search . '%');
            });
        }

        $assets = $query->get();

        $stats = [
            'total_assets' => $assets->sum('quantity'),
            'total_value' => $assets->sum('value'),
            'active' => $assets->where('status', 'active')->count(),
            'maintenance' => $assets->where('status', 'maintenance')->count(),
            'decommissioned' => $assets->where('status', 'decommissioned')->count(),
        ];

        // Return HTML view optimized for printing
        return view('reports.print', compact('assets', 'stats'));
    }

    public function exportExcel(Request $request)
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('category_id')) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('custom_id', 'like', '%' . $search . '%');
            });
        }

        $assets = $query->get();

        // Create CSV
        $filename = 'assets-report-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID Personalizado',
                'Nombre',
                'Categoría',
                'Subcategoría',
                'Ubicación',
                'Estado',
                'Cantidad',
                'Valor',
                'Proveedor',
                'Fecha de Compra',
                'Placa Municipal'
            ]);

            // Data
            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->custom_id,
                    $asset->name,
                    $asset->subcategory->category->name ?? 'N/A',
                    $asset->subcategory->name ?? 'N/A',
                    $asset->location->name ?? 'N/A',
                    ucfirst($asset->status),
                    $asset->quantity,
                    $asset->value,
                    $asset->supplier->name ?? 'N/A',
                    $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : 'N/A',
                    $asset->municipality_plate ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
