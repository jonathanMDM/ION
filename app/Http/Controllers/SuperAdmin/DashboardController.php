<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_companies' => Company::where('name', '!=', 'Empresa Principal')->count(),
            'total_users' => User::whereHas('company', function ($q) {
                $q->where('name', '!=', 'Empresa Principal');
            })->where('is_superadmin', false)->count(),
            'total_assets' => Asset::withoutGlobalScope(\App\Scopes\CompanyScope::class)->count(),
            'active_companies' => Company::where('name', '!=', 'Empresa Principal')->where('status', 'active')->count(),
        ];

        $recent_companies = Company::where('name', '!=', 'Empresa Principal')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recent_companies'));
    }

    public function systemStatus()
    {
        $companies = Company::withCount(['users', 'assets'])->get()->map(function ($company) {
            // Calculate some "health" metrics
            $company->last_active = $company->users()->latest('updated_at')->first()?->updated_at;
            $company->storage_usage = 'N/A'; // Placeholder for future implementation
            return $company;
        });

        return view('superadmin.system-status', compact('companies'));
    }
}
