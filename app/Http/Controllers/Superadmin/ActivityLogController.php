<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        $users = \App\Models\User::orderBy('name')->get();
        $companies = \App\Models\Company::orderBy('name')->get();
        
        return view('superadmin.activity-logs', compact('logs', 'users', 'companies'));
    }

    public function export()
    {
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
