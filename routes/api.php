<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\AssetController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\AssetApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public status check (no auth required - for public status pages)
Route::get('/status', [HealthController::class, 'status']);

// Protected health check (requires authentication)
Route::middleware(['api.token', 'throttle:60,1'])->get('/health', [HealthController::class, 'health']);

// ========================================
// NEW: Sanctum-based API (v2)
// ========================================
Route::prefix('v2')->group(function () {
    
    // Public authentication endpoints
    Route::post('/auth/login', [AuthApiController::class, 'login']);
    
    // Protected endpoints (require Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth endpoints
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);
        Route::get('/auth/user', [AuthApiController::class, 'user']);
        Route::post('/auth/refresh', [AuthApiController::class, 'refresh']);
        
        // Asset endpoints
        Route::apiResource('assets', AssetApiController::class)->names([
            'index' => 'api.assets.index',
            'store' => 'api.assets.store',
            'show' => 'api.assets.show',
            'update' => 'api.assets.update',
            'destroy' => 'api.assets.destroy',
        ]);
        
        // Category endpoints
        Route::apiResource('categories', \App\Http\Controllers\Api\CategoryApiController::class)->names([
            'index' => 'api.categories.index',
            'store' => 'api.categories.store',
            'show' => 'api.categories.show',
            'update' => 'api.categories.update',
            'destroy' => 'api.categories.destroy',
        ]);
        
        // Location endpoints
        Route::apiResource('locations', \App\Http\Controllers\Api\LocationApiController::class)->names([
            'index' => 'api.locations.index',
            'store' => 'api.locations.store',
            'show' => 'api.locations.show',
            'update' => 'api.locations.update',
            'destroy' => 'api.locations.destroy',
        ]);
        
        // Maintenance endpoints
        Route::apiResource('maintenances', \App\Http\Controllers\Api\MaintenanceApiController::class)->names([
            'index' => 'api.maintenances.index',
            'store' => 'api.maintenances.store',
            'show' => 'api.maintenances.show',
            'update' => 'api.maintenances.update',
            'destroy' => 'api.maintenances.destroy',
        ]);
        
        // User endpoints
        Route::apiResource('users', \App\Http\Controllers\Api\UserApiController::class)->names([
            'index' => 'api.users.index',
            'store' => 'api.users.store',
            'show' => 'api.users.show',
            'update' => 'api.users.update',
            'destroy' => 'api.users.destroy',
        ]);
        
    });
});

// ========================================
// EXISTING: API v1 routes (keep for backwards compatibility)
// ========================================
Route::prefix('v1')->group(function () {
    
    // Authentication endpoints (require web auth)
    Route::middleware('auth')->prefix('auth')->group(function () {
        Route::post('/token/generate', [AuthController::class, 'generateToken']);
        Route::delete('/token/revoke', [AuthController::class, 'revokeToken']);
        Route::get('/token/status', [AuthController::class, 'tokenStatus']);
    });
    
    // Protected API endpoints (require API token)
    Route::middleware(['api.token', 'throttle:60,1'])->group(function () {
        
        // User endpoints
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/me', [UserController::class, 'me']);
            Route::get('/{id}', [UserController::class, 'show']);
        });
        
        // Asset endpoints
        Route::prefix('assets')->group(function () {
            Route::get('/', [AssetController::class, 'index']);
            Route::get('/{id}', [AssetController::class, 'show']);
            Route::post('/', [AssetController::class, 'store']);
            Route::put('/{id}', [AssetController::class, 'update']);
            Route::patch('/{id}', [AssetController::class, 'update']);
            Route::delete('/{id}', [AssetController::class, 'destroy']);
        });
        
        // Company endpoints
        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'index']);
            Route::get('/{id}', [CompanyController::class, 'show']);
            Route::get('/{id}/stats', [CompanyController::class, 'stats']);
        });
        
    });
});
