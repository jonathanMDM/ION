<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener IDs de empresas que solo tienen superadmins o no tienen usuarios
        $superadminCompanyIds = Company::whereDoesntHave('users', function($query) {
            $query->where('role', '!=', 'superadmin');
        })->pluck('id');
        
        $stats = [
            // Solo contar empresas de clientes (excluir empresas sin usuarios o solo con superadmin)
            'total_companies' => Company::whereNotIn('id', $superadminCompanyIds)->count(),
            
            'active_companies' => Company::where('status', 'active')
                ->whereNotIn('id', $superadminCompanyIds)
                ->count(),
            
            // Solo contar usuarios que NO son superadmin
            'total_users' => User::where('role', '!=', 'superadmin')->count(),
            
            'total_assets' => Asset::count(),
        ];

        // Solo mostrar empresas de clientes (no las del superadmin)
        $recent_companies = Company::whereNotIn('id', $superadminCompanyIds)
            ->latest()
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recent_companies'));
    }

    public function systemStatus()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => config('database.default'),
            'cache_driver' => config('cache.default'),
        ];

        // Excluir empresas que solo tienen superadmins o no tienen usuarios
        $companies = Company::with('users')
            ->where('nit', '!=', 'N/A')
            ->whereHas('users', function($query) {
                $query->where('role', '!=', 'superadmin');
            })
            ->latest()
            ->get();

        return view('superadmin.system-status', compact('systemInfo', 'companies'));
    }
}
