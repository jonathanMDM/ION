<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\FieldConfig;
use App\Helpers\FieldHelper;
use Illuminate\Http\Request;

class FieldConfigController extends Controller
{
    public function index()
    {
        // Define available fields to configure
        $availableFields = [
            'municipality_plate' => 'Placa Municipio',
            // Add more fields here as needed
        ];

        $roles = ['admin', 'editor', 'viewer'];
        
        // Get existing configs
        $configs = FieldConfig::all()->groupBy('role');

        return view('superadmin.fields.index', compact('availableFields', 'roles', 'configs'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.role' => 'required|string',
            'configs.*.field_name' => 'required|string',
            'configs.*.is_visible' => 'required|boolean',
        ]);

        foreach ($validated['configs'] as $configData) {
            FieldConfig::updateOrCreate(
                [
                    'role' => $configData['role'],
                    'field_name' => $configData['field_name'],
                ],
                [
                    'is_visible' => $configData['is_visible'],
                    'label' => $request->input("labels.{$configData['field_name']}"),
                ]
            );
        }

        // Clear cache so changes take effect immediately
        FieldHelper::clearCache();

        return redirect()->route('superadmin.fields.index')
            ->with('success', 'Configuración de campos actualizada correctamente.');
    }
}
