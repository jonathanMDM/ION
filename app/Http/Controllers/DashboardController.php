<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (\Illuminate\Support\Facades\Auth::user()->isSuperAdmin()) {
            return redirect()->route('superadmin.index');
        }

        // Statistics
        $stats = [
            'total_assets' => Asset::count(),
            'active_assets' => Asset::where('status', 'active')->count(),
            'maintenance_assets' => Asset::where('status', 'maintenance')->count(),
            'decommissioned_assets' => Asset::where('status', 'decommissioned')->count(),
            'total_locations' => Location::count(),
            'total_categories' => Category::count(),
            'total_maintenances' => Maintenance::count(),
        ];

        // Recent Activity
        $recent_assets = Asset::with(['location', 'subcategory.category'])->latest()->take(5)->get();

        // Active Announcements
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->where(function($query) {
                $query->where('target_audience', 'all')
                    ->orWhere(function($q) {
                        $q->where('target_audience', 'specific_company')
                          ->where('company_id', \Auth::user()->company_id);
                    });
            })
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->latest()
            ->get();

        return view('dashboard', compact('stats', 'recent_assets', 'announcements'));
    }
}
