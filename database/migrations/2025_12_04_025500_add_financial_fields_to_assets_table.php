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
            if (!Schema::hasColumn('assets', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable()->after('purchase_date');
            }
            if (!Schema::hasColumn('assets', 'current_value')) {
                $table->decimal('current_value', 10, 2)->nullable()->after('purchase_price');
            }
            // If 'value' exists, we might want to drop it or keep it. 
            // For now, let's assume 'value' was intended as one of these but we are adding explicit columns.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['purchase_price', 'current_value']);
        });
    }
};
