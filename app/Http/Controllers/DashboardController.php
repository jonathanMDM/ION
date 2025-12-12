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
            'total_assets' => Asset::sum('quantity'),
            'active_assets' => Asset::where('status', 'active')->sum('quantity'),
            'maintenance_assets' => Asset::where('status', 'maintenance')->sum('quantity'),
            'decommissioned_assets' => Asset::where('status', 'decommissioned')->sum('quantity'),
            'total_locations' => Location::count(),
            'total_categories' => Category::count(),
            'total_maintenances' => Maintenance::count(),
        ];

        // Recent Activity
        $recent_assets = Asset::with(['location', 'subcategory.category'])->latest()->take(5)->get();

        // Low Stock Assets (only if company has alerts enabled)
        $lowStockAssets = collect();
        if (auth()->user()->company && auth()->user()->company->low_stock_alerts_enabled) {
            $lowStockAssets = Asset::with(['location', 'subcategory.category'])
                ->lowStock()
                ->orderBy('quantity', 'asc')
                ->take(10)
                ->get();
        }

        // Active Announcements for current user
        $announcements = \App\Models\Announcement::active()
            ->forUser(\Auth::user())
            ->latest()
            ->get();

        return view('dashboard', compact('stats', 'recent_assets', 'announcements', 'lowStockAssets'));
    }
}
