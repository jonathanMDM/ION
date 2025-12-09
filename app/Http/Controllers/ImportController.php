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
        return Excel::download(new AssetsTemplateExport, 'plantilla_activos.xlsx');
    }
}
