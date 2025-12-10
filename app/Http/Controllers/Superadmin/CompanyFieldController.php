<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomField;
use App\Models\FieldVisibility;
use App\Helpers\FieldHelper;
use Illuminate\Http\Request;

class CompanyFieldController extends Controller
{
    public function index(Company $company)
    {
        $fields = CustomField::where('company_id', $company->id)->get();
        return view('superadmin.companies.fields', compact('company', 'fields'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select,textarea',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        // Generate internal name from label
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
        return redirect()->back()->with('success', 'Campo eliminado exitosamente.');
    }

    public function updateVisibility(Request $request, Company $company)
    {
        $validated = $request->validate([
            'field_key' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'role' => 'nullable|in:admin,editor,viewer',
            'is_visible' => 'required|boolean',
        ]);

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

        return redirect()->back()->with('success', 'Visibilidad actualizada exitosamente.');
    }
}
