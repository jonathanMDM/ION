<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Superadmin can see all logs, bypass CompanyScope
        $logs = ActivityLog::withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->with('user')
            ->latest()
            ->paginate(50);
            
        $users = \App\Models\User::withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->orderBy('name')
            ->get();
            
        $companies = \App\Models\Company::orderBy('name')->get();
        
        return view('superadmin.activity-logs', compact('logs', 'users', 'companies'));
    }

    public function export()
    {
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
