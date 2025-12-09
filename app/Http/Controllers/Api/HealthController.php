<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Company;
use App\Models\Asset;

class HealthController
{
    /**
     * Simple health check endpoint
     * Public - no authentication required
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Detailed system status endpoint
     * Public - no authentication required
     */
    public function status(): JsonResponse
    {
        $status = [
            'status' => 'operational',
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'checks' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
            ],
            'statistics' => [
                'total_companies' => Company::count(),
                'total_users' => User::count(),
                'total_assets' => Asset::count(),
            ],
        ];

        // Determine overall status
        $allChecksOk = collect($status['checks'])->every(fn($check) => $check['status'] === 'ok');
        $status['status'] = $allChecksOk ? 'operational' : 'degraded';

        return response()->json($status);
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
            ];
        }
    }

    /**
     * Check cache functionality
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            Cache::put($key, 'test', 10);
            $value = Cache::get($key);
            Cache::forget($key);
            
            return [
                'status' => $value === 'test' ? 'ok' : 'error',
                'message' => $value === 'test' ? 'Cache working' : 'Cache not working',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache check failed',
            ];
        }
    }
}
