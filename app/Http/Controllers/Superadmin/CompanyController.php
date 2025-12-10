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
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'user_limit' => 'required|integer|min:1',
            'subscription_expires_at' => 'nullable|date',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        // Create company
        $company = Company::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nit' => $validated['nit'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'user_limit' => $validated['user_limit'],
            'subscription_expires_at' => $validated['subscription_expires_at'],
            'status' => 'active',
        ]);

        // Create admin user for the company
        \App\Models\User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => bcrypt($validated['admin_password']),
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa y administrador creados exitosamente.');
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
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'user_limit' => 'required|integer|min:1',
            'subscription_expires_at' => 'nullable|date',
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
