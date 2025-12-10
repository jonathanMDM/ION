<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(15);
        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('superadmin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'nit' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Company::create($validated);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa creada exitosamente.');
    }

    public function show(Company $company)
    {
        return view('superadmin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('superadmin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'nit' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $company->update($validated);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa actualizada exitosamente.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa eliminada exitosamente.');
    }
}
