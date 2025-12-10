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
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_assets' => Asset::count(),
        ];

        $recent_companies = Company::latest()->take(10)->get();

        return view('superadmin.dashboard', compact('stats', 'recent_companies'));
    }
}
