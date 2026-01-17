<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        // Exclude the main company (NIT: N/A) and companies with only superadmin users
        $companies = Company::withCount(['users', 'assets'])
            ->with('users')
            ->where('nit', '!=', 'N/A')
            ->whereHas('users', function($query) {
                $query->where('role', '!=', 'superadmin');
            })
            ->latest()
            ->paginate(15);
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
            'nit' => 'nullable|string|max:255|unique:companies,nit',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'user_limit' => 'required|integer|min:1',
            'subscription_expires_at' => 'nullable|date',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
        ]);

        // Generate random password
        $temporaryPassword = \Illuminate\Support\Str::random(12);

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
        $user = \App\Models\User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => bcrypt($temporaryPassword),
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        // Send welcome email with credentials
        try {
            \Mail::to($user->email)->send(new \App\Mail\WelcomeCompanyAdmin($company, $user, $temporaryPassword));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa y administrador creados exitosamente. Se ha enviado un correo con las credenciales.');
    }

    public function show(Company $company)
    {
        $company->loadCount(['users', 'assets']);
        $company->load('invoices');
        
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
            'nit' => 'nullable|string|max:255|unique:companies,nit,' . $company->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'user_limit' => 'required|integer|min:1',
            'subscription_expires_at' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Process enabled_modules
        $enabledModules = [];
        foreach (Company::getDefaultModules() as $key => $default) {
            $enabledModules[$key] = isset($request->enabled_modules[$key]) && $request->enabled_modules[$key] == '1';
        }
        $validated['enabled_modules'] = $enabledModules;

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

    /**
     * Toggle low stock alerts for a company
     */
    public function toggleLowStockAlerts(Company $company)
    {
        $company->update([
            'low_stock_alerts_enabled' => !$company->low_stock_alerts_enabled
        ]);

        $status = $company->low_stock_alerts_enabled ? 'activadas' : 'desactivadas';

        return redirect()->back()
            ->with('success', "Alertas de stock bajo {$status} para {$company->name}.");
    }
}
