<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'user.company'])
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(50);
        $users = User::withoutGlobalScope(\App\Scopes\CompanyScope::class)->orderBy('name')->get();
        $companies = Company::where('name', '!=', 'Empresa Principal')->orderBy('name')->get();

        return view('superadmin.activity-logs', compact('logs', 'users', 'companies'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with(['user', 'user.company'])
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class);

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('company_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Fecha', 'Usuario', 'Empresa', 'Acción', 'Descripción', 'IP', 'User Agent']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'N/A',
                    $log->user->company->name ?? 'N/A',
                    $log->action,
                    $log->description,
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
