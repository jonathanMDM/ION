<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->json('enabled_modules')->nullable()->after('low_stock_alerts_enabled');
        });

        // Set default modules for existing companies
        DB::table('companies')->update([
            'enabled_modules' => json_encode([
                'financial_control' => true,
                'depreciation' => true,
                'cost_centers' => true,
                'asset_costs' => true,
                'transfers' => false,
                'loans' => false,
                'disposals' => false,
                'advanced_audit' => false,
                'compliance' => false,
            ])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('enabled_modules');
        });
    }
};
