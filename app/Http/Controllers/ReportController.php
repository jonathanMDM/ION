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

        // Generate PDF using TCPDF
        $pdf = new \TCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('ION Inventory');
        $pdf->SetAuthor(\Auth::user()->company->name ?? 'ION');
        $pdf->SetTitle('Reporte de Activos');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Activos', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, \Auth::user()->company->name ?? 'ION Inventory', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Fecha: ' . date('d/m/Y H:i'), 0, 1, 'C');
        $pdf->Ln(5);
        
        // Stats
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(50, 7, 'Total: ' . $stats['total_assets'], 1, 0, 'C', false);
        $pdf->Cell(50, 7, 'Valor: $' . number_format($stats['total_value'], 2), 1, 0, 'C', false);
        $pdf->Cell(50, 7, 'Activos: ' . $stats['active'], 1, 0, 'C', false);
        $pdf->Cell(50, 7, 'Mant.: ' . $stats['maintenance'], 1, 0, 'C', false);
        $pdf->Cell(50, 7, 'Baja: ' . $stats['decommissioned'], 1, 1, 'C', false);
        $pdf->Ln(5);
        
        // Table header
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(51, 51, 51);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(20, 7, 'ID', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Categoría', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Ubicación', 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'Estado', 1, 0, 'C', true);
        $pdf->Cell(15, 7, 'Cant.', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Valor', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Proveedor', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'F. Compra', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(0, 0, 0);
        $fill = false;
        
        foreach ($assets as $asset) {
            $pdf->SetFillColor(249, 249, 249);
            $pdf->Cell(20, 6, $asset->custom_id, 1, 0, 'L', $fill);
            $pdf->Cell(40, 6, substr($asset->name, 0, 25), 1, 0, 'L', $fill);
            $pdf->Cell(35, 6, substr(($asset->subcategory->category->name ?? 'N/A'), 0, 20), 1, 0, 'L', $fill);
            $pdf->Cell(30, 6, substr(($asset->location->name ?? 'N/A'), 0, 18), 1, 0, 'L', $fill);
            $pdf->Cell(20, 6, ucfirst($asset->status), 1, 0, 'C', $fill);
            $pdf->Cell(15, 6, $asset->quantity, 1, 0, 'C', $fill);
            $pdf->Cell(25, 6, '$' . number_format($asset->value, 2), 1, 0, 'R', $fill);
            $pdf->Cell(35, 6, substr(($asset->supplier->name ?? 'N/A'), 0, 20), 1, 0, 'L', $fill);
            $pdf->Cell(25, 6, $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : 'N/A', 1, 1, 'C', $fill);
            $fill = !$fill;
        }
        
        // Output PDF
        return response($pdf->Output('reporte-activos-' . date('Y-m-d') . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="reporte-activos-' . date('Y-m-d') . '.pdf"');
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
