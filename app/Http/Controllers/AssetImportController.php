<?php

namespace App\Http\Controllers;

use App\Services\AssetImportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssetImportController extends Controller
{
    protected $importService;

    public function __construct(AssetImportService $importService)
    {
        $this->importService = $importService;
    }

    public function showForm()
    {
        return view('assets.import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        $result = $this->importService->validateAndPreview($request->file('file'));

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Guardar datos en sesión para la importación
        session(['import_data' => $result]);

        return view('assets.import_preview', $result);
    }

    public function import()
    {
        $importData = session('import_data');

        if (!$importData) {
            return redirect()->route('assets.import')->with('error', 'No hay datos para importar');
        }

        $result = $this->importService->import($importData['valid_rows']);

        session()->forget('import_data');

        $message = "Importación completada: {$result['imported']} activos importados";
        if (!empty($result['failed'])) {
            $message .= ", {$count($result['failed'])} fallidos";
        }

        return redirect()->route('assets.index')->with('success', $message);
    }

    public function downloadTemplate()
    {
        $headers = [
            'custom_id',
            'name',
            'model',
            'location',
            'category',
            'subcategory',
            'supplier',
            'value',
            'quantity',
            'purchase_date',
            'municipality_plate',
            'specifications',
            'status'
        ];

        $example = [
            'ACT-001',
            'Laptop Dell Latitude',
            'Latitude 5420',
            'Oficina Principal',
            'Tecnología',
            'Computadoras',
            'Dell Inc',
            '1500000',
            '1',
            '2024-01-15',
            'MUN-001',
            'Intel i7, 16GB RAM, 512GB SSD',
            'active'
        ];

        $data = [$headers, $example];

        return response()->streamDownload(function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'plantilla_activos.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
