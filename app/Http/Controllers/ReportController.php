<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Category;
use App\Models\AssetMovement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier', 'costCenter']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('cost_center_id')) {
            $query->where('cost_center_id', $request->cost_center_id);
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

        $assets = $query->orderBy('purchase_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_assets' => $assets->sum('quantity'),
            'total_purchase_price' => $assets->sum('purchase_price'),
            'total_current_value' => $assets->sum('value'),
            'active' => $assets->where('status', 'active')->count(),
            'maintenance' => $assets->where('status', 'maintenance')->count(),
            'decommissioned' => $assets->where('status', 'decommissioned')->count(),
            'with_plate' => $assets->whereNotNull('municipality_plate')->where('municipality_plate', '!=', '')->count(),
        ];

        $locations = Location::all();
        $categories = Category::all();
        $subcategories = \App\Models\Subcategory::with('category')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $costCenters = \App\Models\CostCenter::where('company_id', \Auth::user()->company_id)->orderBy('name')->get();

        return view('reports.index', compact('assets', 'stats', 'locations', 'categories', 'subcategories', 'suppliers', 'costCenters'));
    }

    public function movements(Request $request)
    {
        $query = AssetMovement::with(['asset' => function($q) {
            $q->withTrashed();
        }, 'fromLocation', 'toLocation', 'user'])
        ->whereHas('asset', function($q) {
            $q->where('company_id', auth()->user()->company_id);
        });

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('date_from')) {
            $query->where('moved_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('moved_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $movements = $query->orderBy('moved_at', 'desc')->paginate(50);
        $users = \App\Models\User::where('company_id', auth()->user()->company_id)->get();
        $assets = Asset::orderBy('name')->get();

        return view('reports.movements', compact('movements', 'users', 'assets'));
    }

    public function exportPdf(Request $request)
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier', 'costCenter']);

        // Apply filters (same as index)
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('cost_center_id')) $query->where('cost_center_id', $request->cost_center_id);
        if ($request->filled('category_id')) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        if ($request->filled('date_from')) $query->where('purchase_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->where('purchase_date', '<=', $request->date_to);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('custom_id', 'like', '%' . $search . '%')
                  ->orWhere('municipality_plate', 'like', '%' . $search . '%')
                  ->orWhere('specifications', 'like', '%' . $search . '%');
            });
        }

        $assets = $query->orderBy('purchase_date', 'desc')->get();

        $stats = [
            'total_assets' => $assets->sum('quantity'),
            'total_purchase_price' => $assets->sum('purchase_price'),
            'total_current_value' => $assets->sum('value'),
            'active' => $assets->where('status', 'active')->count(),
            'maintenance' => $assets->where('status', 'maintenance')->count(),
            'decommissioned' => $assets->where('status', 'decommissioned')->count(),
        ];

        return view('reports.print', compact('assets', 'stats'));
    }

    public function exportExcel(Request $request)
    {
        $query = Asset::with(['location', 'subcategory.category', 'supplier', 'costCenter']);

        // Apply same filters as index
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('cost_center_id')) $query->where('cost_center_id', $request->cost_center_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('custom_id', 'like', '%' . $search . '%')
                  ->orWhere('municipality_plate', 'like', '%' . $search . '%')
                  ->orWhere('specifications', 'like', '%' . $search . '%');
            });
        }

        $assets = $query->orderBy('purchase_date', 'desc')->get();

        // Create CSV
        $filename = 'assets-report-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'ID Personalizado',
                'Nombre',
                'Categoría',
                'Subcategoría',
                'Ubicación',
                'Centro de Costo',
                'Estado',
                'Cantidad',
                'Precio Compra',
                'Valor Actual',
                'Proveedor',
                'Fecha de Compra',
                'Placa Municipal'
            ]);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->custom_id,
                    $asset->name,
                    $asset->subcategory->category->name ?? 'N/A',
                    $asset->subcategory->name ?? 'N/A',
                    $asset->location->name ?? 'N/A',
                    $asset->costCenter->name ?? 'N/A',
                    ucfirst($asset->status),
                    $asset->quantity,
                    $asset->purchase_price,
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
