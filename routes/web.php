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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// 2FA Verification Routes (for login)
Route::get('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
Route::post('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify']);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/offline', 'offline');

// Force Password Change (must be outside main auth middleware to avoid redirect loop)
Route::middleware(['auth'])->group(function () {
    Route::get('/force-password-change', [App\Http\Controllers\Auth\ForcePasswordChangeController::class, 'show'])->name('force-password-change');
    Route::post('/force-password-change', [App\Http\Controllers\Auth\ForcePasswordChangeController::class, 'update'])->name('force-password-change.update');
});


// Protected routes
Route::middleware(['auth'])->group(function () {
    // Support Validation
    Route::get('support/validation', [App\Http\Controllers\Superadmin\SupportValidationController::class, 'index'])->name('superadmin.support.validation');
    Route::post('support/validation', [App\Http\Controllers\Superadmin\SupportValidationController::class, 'validateCustomer'])->name('superadmin.support.validate');

    // Support Tickets
    Route::prefix('superadmin/tickets')->name('superadmin.tickets.')->middleware('superadmin')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\SupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\SupportTicketController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Superadmin\SupportTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [App\Http\Controllers\Superadmin\SupportTicketController::class, 'show'])->name('show');
        Route::put('/{ticket}', [App\Http\Controllers\Superadmin\SupportTicketController::class, 'update'])->name('update');
        Route::post('/{ticket}/notes', [App\Http\Controllers\Superadmin\SupportTicketController::class, 'addNote'])->name('add-note');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [\App\Http\Controllers\UserProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/settings/preferences', [\App\Http\Controllers\UserProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Support Tickets (Client Side)
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SupportTicketController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [\App\Http\Controllers\SupportTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/notes', [\App\Http\Controllers\SupportTicketController::class, 'addNote'])->name('add-note');
    });
    
    // Change password
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.change.update');
    
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
    Route::post('/assets/{asset}/withdraw', [AssetController::class, 'withdraw'])->name('assets.withdraw');
    Route::get('/assets/{asset}/qr', [AssetController::class, 'showQR'])->name('assets.qr');
    
    // Asset Movements
    Route::resource('asset-movements', AssetMovementController::class)->only(['index', 'show', 'store']);
    
    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    // Cost Centers
    Route::resource('cost-centers', \App\Http\Controllers\CostCenterController::class);
    Route::post('cost-centers/{costCenter}/toggle-status', [\App\Http\Controllers\CostCenterController::class, 'toggleStatus'])->name('cost-centers.toggle-status');
    
    // Asset Costs
    Route::get('assets/{asset}/costs', [\App\Http\Controllers\AssetCostController::class, 'index'])->name('assets.costs.index');
    Route::get('assets/{asset}/costs/create', [\App\Http\Controllers\AssetCostController::class, 'create'])->name('assets.costs.create');
    Route::post('assets/{asset}/costs', [\App\Http\Controllers\AssetCostController::class, 'store'])->name('assets.costs.store');
    Route::get('assets/{asset}/costs/{cost}', [\App\Http\Controllers\AssetCostController::class, 'show'])->name('assets.costs.show');
    Route::get('assets/{asset}/costs/{cost}/edit', [\App\Http\Controllers\AssetCostController::class, 'edit'])->name('assets.costs.edit');
    Route::put('assets/{asset}/costs/{cost}', [\App\Http\Controllers\AssetCostController::class, 'update'])->name('assets.costs.update');
    Route::delete('assets/{asset}/costs/{cost}', [\App\Http\Controllers\AssetCostController::class, 'destroy'])->name('assets.costs.destroy');
    Route::get('assets/{asset}/costs/{cost}/download', [\App\Http\Controllers\AssetCostController::class, 'downloadDocument'])->name('assets.costs.download');

    


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
    Route::get('reports/movements', [ReportController::class, 'movements'])->name('reports.movements');
    Route::post('reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::post('reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    


    // User management (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Onboarding
    Route::post('/onboarding/complete', function() {
        Auth::user()->update(['onboarding_completed' => true]);
        return response()->json(['success' => true]);
    })->name('onboarding.complete');
});

// Superadmin Routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('index');
    Route::resource('companies', \App\Http\Controllers\Superadmin\CompanyController::class);
    Route::post('companies/{company}/toggle-low-stock-alerts', [\App\Http\Controllers\Superadmin\CompanyController::class, 'toggleLowStockAlerts'])->name('companies.toggle-low-stock-alerts');
    
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

    // Invoices
    Route::get('companies/{company}/invoices/create', [\App\Http\Controllers\Superadmin\InvoiceController::class, 'create'])->name('companies.invoices.create');
    Route::post('companies/{company}/invoices', [\App\Http\Controllers\Superadmin\InvoiceController::class, 'store'])->name('companies.invoices.store');
    Route::get('invoices/{invoice}/download', [\App\Http\Controllers\Superadmin\InvoiceController::class, 'download'])->name('invoices.download');
});

// Stop Impersonation
Route::post('impersonate/stop', [\App\Http\Controllers\Superadmin\UserController::class, 'stopImpersonating'])->name('impersonate.stop')->middleware('auth');
