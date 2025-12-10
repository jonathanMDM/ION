<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('superadmin.activity-logs', compact('logs'));
    }

    public function export()
    {
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
