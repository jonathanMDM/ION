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
            if (!Schema::hasColumn('assets', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('custom_id');
            }
            if (!Schema::hasColumn('assets', 'model')) {
                $table->string('model')->nullable()->after('serial_number');
            }
            if (!Schema::hasColumn('assets', 'brand')) {
                $table->string('brand')->nullable()->after('model');
            }
            if (!Schema::hasColumn('assets', 'condition')) {
                $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good')->after('status');
            }
            if (!Schema::hasColumn('assets', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('assets', 'notes')) {
                $table->text('notes')->nullable()->after('condition');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['serial_number', 'model', 'brand', 'condition', 'description', 'notes']);
        });
    }
};
