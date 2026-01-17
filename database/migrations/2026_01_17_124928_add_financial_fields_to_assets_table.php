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
        Schema::table('assets', function (Blueprint $table) {
            // Depreciation fields
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'units_of_production', 'none'])->default('none')->after('purchase_price');
            $table->integer('useful_life_years')->nullable()->after('depreciation_method');
            $table->decimal('salvage_value', 15, 2)->nullable()->after('useful_life_years');
            $table->date('depreciation_start_date')->nullable()->after('salvage_value');
            $table->decimal('accumulated_depreciation', 15, 2)->default(0)->after('depreciation_start_date');
            
            // Cost center
            $table->foreignId('cost_center_id')->nullable()->after('accumulated_depreciation')->constrained('cost_centers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['cost_center_id']);
            $table->dropColumn([
                'depreciation_method',
                'useful_life_years',
                'salvage_value',
                'depreciation_start_date',
                'accumulated_depreciation',
                'cost_center_id'
            ]);
        });
    }
};
