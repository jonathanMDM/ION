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
        // Excluir la empresa del superadmin (company_id null o la primera empresa creada)
        // Asumiendo que la empresa del superadmin tiene un identificador específico
        // o es la que no tiene company_id asignado a usuarios
        
        $stats = [
            // Solo contar empresas de clientes (excluir empresa del superadmin)
            'total_companies' => Company::whereHas('users', function($query) {
                $query->where('role', '!=', 'superadmin');
            })->count(),
            
            'active_companies' => Company::where('status', 'active')
                ->whereHas('users', function($query) {
                    $query->where('role', '!=', 'superadmin');
                })->count(),
            
            // Solo contar usuarios que NO son superadmin
            'total_users' => User::where('role', '!=', 'superadmin')->count(),
            
            'total_assets' => Asset::count(),
        ];

        // Solo mostrar empresas de clientes (no la del superadmin)
        $recent_companies = Company::whereHas('users', function($query) {
            $query->where('role', '!=', 'superadmin');
        })->latest()->take(10)->get();

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

        $companies = Company::with('users')->latest()->get();

        return view('superadmin.system-status', compact('systemInfo', 'companies'));
    }
}
