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

        // Specific Stats for Charts/Widgets
        $assets_per_location = Location::withCount('assets')
            ->orderBy('assets_count', 'desc')
            ->take(5)
            ->get();

        // Recent Activity
        $recentAssets = Asset::with(['location', 'subcategory.category'])->latest()->take(5)->get();

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

        // Check subscription expiration
        $subscriptionWarning = null;
        $company = auth()->user()->company;
        if ($company && $company->subscription_expires_at) {
            $daysLeft = (int) now()->diffInDays($company->subscription_expires_at, false);
            if ($daysLeft <= 15 && $daysLeft > 0) {
                $subscriptionWarning = [
                    'days_left' => $daysLeft,
                    'expires_at' => $company->subscription_expires_at->format('d/m/Y'),
                    'is_critical' => $daysLeft <= 7,
                ];
            } elseif ($daysLeft <= 0) {
                $subscriptionWarning = [
                    'days_left' => 0,
                    'expires_at' => $company->subscription_expires_at->format('d/m/Y'),
                    'is_expired' => true,
                ];
            }
        }

        return view('dashboard', compact('stats', 'recentAssets', 'assets_per_location', 'announcements', 'lowStockAssets', 'subscriptionWarning'));
    }
}
