<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\AssetController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CompanyController;

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

// API v1 routes
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
