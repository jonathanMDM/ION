<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\FieldVisibility;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\FieldHelper;

class CompanyFieldController extends Controller
{
    public function index(Company $company)
    {
        $customFields = CustomField::where('company_id', $company->id)->get();
        $users = User::where('company_id', $company->id)->get();
        
        // System fields that can be toggled
        $systemFields = [
            'municipality_plate' => 'Placa Municipio',
            'model' => 'Modelo',
            'serial_number' => 'Número de Serie',
            'purchase_price' => 'Precio de Compra',
        ];

        return view('superadmin.companies.fields', compact('company', 'customFields', 'systemFields', 'users'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select,textarea',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        // Generate internal name
        $name = \Illuminate\Support\Str::slug($validated['label'], '_');
        
        // Ensure unique name for company
        $count = CustomField::where('company_id', $company->id)
            ->where('name', 'like', $name . '%')
            ->count();
        
        if ($count > 0) {
            $name = $name . '_' . ($count + 1);
        }

        $options = null;
        if ($validated['type'] === 'select' && !empty($validated['options'])) {
            $options = array_map('trim', explode(',', $validated['options']));
        }

        CustomField::create([
            'company_id' => $company->id,
            'name' => $name,
            'label' => $validated['label'],
            'type' => $validated['type'],
            'options' => $options,
            'is_required' => $request->boolean('is_required'),
        ]);

        return redirect()->back()->with('success', 'Campo personalizado creado exitosamente.');
    }

    public function destroy(Company $company, CustomField $customField)
    {
        if ($customField->company_id !== $company->id) {
            abort(403);
        }

        $customField->delete();
        return redirect()->back()->with('success', 'Campo eliminado.');
    }

    public function updateVisibility(Request $request, Company $company)
    {
        $validated = $request->validate([
            'field_key' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'role' => 'nullable|in:admin,editor,viewer',
            'is_visible' => 'required|boolean',
        ]);

        // Verify user belongs to company if set
        if ($validated['user_id']) {
            $user = User::find($validated['user_id']);
            if ($user->company_id !== $company->id) {
                abort(403, 'El usuario no pertenece a esta empresa.');
            }
        }

        FieldVisibility::updateOrCreate(
            [
                'company_id' => $company->id,
                'field_key' => $validated['field_key'],
                'user_id' => $validated['user_id'],
                'role' => $validated['role'],
            ],
            [
                'is_visible' => $validated['is_visible']
            ]
        );

        FieldHelper::clearCache();

        return redirect()->back()->with('success', 'Visibilidad actualizada.');
    }
}
