<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetMovementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AssetImportController;
use App\Http\Controllers\SupportController;

// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/offline', 'offline');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Support Validation
    Route::get('support/validation', [App\Http\Controllers\Superadmin\SupportValidationController::class, 'index'])->name('superadmin.support.validation');
    Route::post('support/validation', [App\Http\Controllers\Superadmin\SupportValidationController::class, 'validateCustomer'])->name('superadmin.support.validate');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [\App\Http\Controllers\UserProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/settings/preferences', [\App\Http\Controllers\UserProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
    
    // Change password
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');
    
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{supportRequest}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{supportRequest}/respond', [SupportController::class, 'respond'])->name('support.respond');
    Route::post('/support/{supportRequest}/resolve', [SupportController::class, 'resolve'])->name('support.resolve');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Import Assets (must be before resource route)
    Route::get('imports/create', [ImportController::class, 'create'])->name('imports.create');
    Route::post('imports/store', [ImportController::class, 'store'])->name('imports.store');
    Route::get('imports/template', [ImportController::class, 'downloadTemplate'])->name('assets.import.template');
    
    // Assets
    Route::delete('/assets/bulk-delete', [AssetController::class, 'bulkDelete'])->name('assets.bulk-delete');
    Route::resource('assets', AssetController::class);
    Route::get('/assets/{asset}/qr', [AssetController::class, 'showQR'])->name('assets.qr');
    
    // Asset Movements
    Route::resource('asset-movements', AssetMovementController::class)->only(['index', 'show', 'store']);
    
    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    

    


    // Search
    Route::get('search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');
    
    // Scanner
    Route::get('scanner', [\App\Http\Controllers\ScannerController::class, 'index'])->name('scanner.index');
    
    // Two-Factor Authentication
    Route::get('two-factor', [\App\Http\Controllers\TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('two-factor/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::delete('two-factor/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('two-factor.disable');
    

    
    // Backups (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('backups', [\App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
        Route::post('backups/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backups.create');
        Route::get('backups/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
        Route::delete('backups/delete/{filename}', [\App\Http\Controllers\BackupController::class, 'delete'])->name('backups.delete');
        Route::post('backups/upload', [\App\Http\Controllers\BackupController::class, 'upload'])->name('backups.upload');
        Route::post('backups/restore/{filename}', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backups.restore');
    });


    // Assets
    Route::get('assets/export-template', [\App\Http\Controllers\AssetImportController::class, 'downloadTemplate'])->name('assets.export-template');
    
    // Employees
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    
    // Assignments
    Route::get('assets/{asset}/assign', [\App\Http\Controllers\AssetAssignmentController::class, 'create'])->name('assets.assign');
    Route::post('assets/{asset}/assign', [\App\Http\Controllers\AssetAssignmentController::class, 'store'])->name('assets.assign.store');
    Route::post('assignments/{assignment}/return', [\App\Http\Controllers\AssetAssignmentController::class, 'returnAsset'])->name('assignments.return');
    
    // Categories
    Route::delete('/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::resource('categories', CategoryController::class);
    
    // Subcategories
    Route::delete('/subcategories/bulk-delete', [SubcategoryController::class, 'bulkDelete'])->name('subcategories.bulk-delete');
    Route::resource('subcategories', SubcategoryController::class);
    
    // Locations
    Route::delete('/locations/bulk-delete', [LocationController::class, 'bulkDelete'])->name('locations.bulk-delete');
    Route::resource('locations', LocationController::class);
    
    // Suppliers
    Route::delete('/suppliers/bulk-delete', [\App\Http\Controllers\SupplierController::class, 'bulkDelete'])->name('suppliers.bulk-delete');
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    
    // Maintenances
    Route::resource('maintenances', MaintenanceController::class);
    
    // Custom Fields & Visibility
    Route::get('settings/fields', [\App\Http\Controllers\CustomFieldController::class, 'index'])->name('settings.fields.index');
    Route::post('settings/fields', [\App\Http\Controllers\CustomFieldController::class, 'store'])->name('settings.fields.store');
    Route::delete('settings/fields/{customField}', [\App\Http\Controllers\CustomFieldController::class, 'destroy'])->name('settings.fields.destroy');
    Route::post('settings/fields/visibility', [\App\Http\Controllers\CustomFieldController::class, 'updateVisibility'])->name('settings.fields.visibility');

    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::post('reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    


    // User management (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

// Superadmin Routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('index');
    Route::resource('companies', \App\Http\Controllers\Superadmin\CompanyController::class);
    
    // Impersonation
    Route::post('users/{user}/impersonate', [\App\Http\Controllers\Superadmin\UserController::class, 'impersonate'])->name('users.impersonate');
    
    // System Status
    Route::get('system-status', [\App\Http\Controllers\Superadmin\DashboardController::class, 'systemStatus'])->name('system-status');
    
    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\Superadmin\ActivityLogController::class, 'index'])->name('activity-logs');
    Route::get('activity-logs/export', [\App\Http\Controllers\Superadmin\ActivityLogController::class, 'export'])->name('activity-logs.export');
    
    // Backups
    Route::get('backups', [\App\Http\Controllers\Superadmin\BackupController::class, 'index'])->name('backups.index');
    Route::post('backups/create/{company}', [\App\Http\Controllers\Superadmin\BackupController::class, 'create'])->name('backups.create');
    Route::get('backups/download/{filename}', [\App\Http\Controllers\Superadmin\BackupController::class, 'download'])->name('backups.download');
    Route::delete('backups/delete/{filename}', [\App\Http\Controllers\Superadmin\BackupController::class, 'delete'])->name('backups.delete');
    Route::post('backups/upload', [\App\Http\Controllers\Superadmin\BackupController::class, 'upload'])->name('backups.upload');
    Route::post('backups/restore/{filename}', [\App\Http\Controllers\Superadmin\BackupController::class, 'restore'])->name('backups.restore');
    
    // Webhooks
    Route::resource('webhooks', \App\Http\Controllers\WebhookController::class);
    
    // Support Requests
    Route::get('support', [\App\Http\Controllers\Superadmin\SuperadminSupportController::class, 'index'])->name('support.index');
    Route::get('support/{supportRequest}', [\App\Http\Controllers\Superadmin\SuperadminSupportController::class, 'show'])->name('support.show');
    Route::patch('support/{supportRequest}/status', [\App\Http\Controllers\Superadmin\SuperadminSupportController::class, 'updateStatus'])->name('support.update-status');
    // Field Configuration
    Route::get('/fields', [\App\Http\Controllers\Superadmin\FieldConfigController::class, 'index'])->name('fields.index');
    Route::post('/fields', [\App\Http\Controllers\Superadmin\FieldConfigController::class, 'update'])->name('fields.update');

    // Company Fields Management
    Route::get('companies/{company}/fields', [\App\Http\Controllers\Superadmin\CompanyFieldController::class, 'index'])->name('companies.fields.index');
    Route::post('companies/{company}/fields', [\App\Http\Controllers\Superadmin\CompanyFieldController::class, 'store'])->name('companies.fields.store');
    Route::delete('companies/{company}/fields/{customField}', [\App\Http\Controllers\Superadmin\CompanyFieldController::class, 'destroy'])->name('companies.fields.destroy');
    Route::post('companies/{company}/fields/visibility', [\App\Http\Controllers\Superadmin\CompanyFieldController::class, 'updateVisibility'])->name('companies.fields.visibility');
    Route::delete('companies/{company}/fields/visibility/{fieldVisibility}', [\App\Http\Controllers\Superadmin\CompanyFieldController::class, 'deleteVisibilityRule'])->name('companies.fields.visibility.delete');

    // Announcements
    Route::resource('announcements', \App\Http\Controllers\Superadmin\AnnouncementController::class);
    Route::patch('announcements/{announcement}/toggle', [\App\Http\Controllers\Superadmin\AnnouncementController::class, 'toggleActive'])->name('announcements.toggle');
    
    // API Token Management (Superadmin only)
    Route::get('api/token', [\App\Http\Controllers\ApiTokenController::class, 'index'])->name('api.token.index');
    Route::post('api/token/generate', [\App\Http\Controllers\ApiTokenController::class, 'generate'])->name('api.token.generate');
    Route::delete('api/token/revoke', [\App\Http\Controllers\ApiTokenController::class, 'revoke'])->name('api.token.revoke');
});

// Stop Impersonation
Route::post('impersonate/stop', [\App\Http\Controllers\Superadmin\UserController::class, 'stopImpersonating'])->name('impersonate.stop')->middleware('auth');
