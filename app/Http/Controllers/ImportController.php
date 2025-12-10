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
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $companyId = \Illuminate\Support\Facades\Auth::user()->company_id;
        Excel::import(new AssetsImport($companyId), $request->file('file'));

        return redirect()->route('assets.index')->with('success', 'Activos importados exitosamente.');
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
