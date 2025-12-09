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
            $table->date('next_maintenance_date')->nullable()->after('status');
            $table->integer('maintenance_frequency_days')->nullable()->after('next_maintenance_date')
                  ->comment('Frecuencia de mantenimiento en días');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['next_maintenance_date', 'maintenance_frequency_days']);
        });
    }
};
