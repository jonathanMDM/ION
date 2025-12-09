<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('name', '!=', 'Empresa Principal')
            ->withCount(['users', 'assets'])
            ->latest()
            ->paginate(10);
        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('superadmin.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'nit' => $request->nit,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        // Create Admin User for this company
        User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa y administrador creados exitosamente.');
    }

    public function show(Company $company)
    {
        $company->load(['users', 'assets']);
        return view('superadmin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('superadmin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
        ]);

        $company->update($request->all());

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa actualizada exitosamente.');
    }

    public function destroy(Company $company)
    {
        // Delete all users associated with this company first
        $company->users()->delete();
        
        // Delete all assets associated with this company
        $company->assets()->delete();
        
        // Delete the company
        $company->delete();

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa y usuarios asociados eliminados exitosamente.');
    }
}
