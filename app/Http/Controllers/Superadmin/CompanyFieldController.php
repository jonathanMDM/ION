<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CompanyFieldController extends Controller
{
    public function index(Company $company)
    {
        $fields = $company->customFields ?? collect();
        return view('superadmin.companies.fields', compact('company', 'fields'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select',
            'required' => 'boolean',
        ]);

        $company->customFields()->create($validated);

        return redirect()->back()->with('success', 'Campo creado exitosamente');
    }

    public function destroy(Company $company, CustomField $customField)
    {
        $customField->delete();

        return redirect()->back()->with('success', 'Campo eliminado exitosamente');
    }

    public function updateVisibility(Request $request, Company $company)
    {
        // Logic for updating field visibility
        return redirect()->back()->with('success', 'Visibilidad actualizada');
    }
}
