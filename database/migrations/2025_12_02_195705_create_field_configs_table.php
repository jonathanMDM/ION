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
        Schema::create('field_configs', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // 'admin', 'editor', 'viewer'
            $table->string('field_name'); // e.g., 'municipality_plate'
            $table->boolean('is_visible')->default(true);
            $table->string('label')->nullable(); // Human readable label
            $table->timestamps();

            // Unique constraint to prevent duplicate configs for same role/field
            $table->unique(['role', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_configs');
    }
};
