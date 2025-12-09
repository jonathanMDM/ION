<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'assets',
            'locations',
            'categories',
            'subcategories',
            'suppliers',
            'employees',
            'maintenances',
            'asset_movements',
            'asset_assignments',
            'webhooks',
            'notifications',
            'activity_logs'
        ];

        // Get default company ID
        $defaultCompanyId = DB::table('companies')->first()->id;

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('company_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                });

                // Assign all existing records to default company
                DB::table($table)->update(['company_id' => $defaultCompanyId]);
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'assets',
            'locations',
            'categories',
            'subcategories',
            'suppliers',
            'employees',
            'maintenances',
            'asset_movements',
            'asset_assignments',
            'webhooks',
            'notifications',
            'activity_logs'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['company_id']);
                    $table->dropColumn('company_id');
                });
            }
        }
    }
};
