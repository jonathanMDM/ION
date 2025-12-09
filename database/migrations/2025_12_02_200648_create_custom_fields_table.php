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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Internal key, e.g., 'warranty_extended'
            $table->string('label'); // Display name, e.g., 'Garantía Extendida'
            $table->string('type'); // 'text', 'number', 'date', 'select', 'textarea'
            $table->json('options')->nullable(); // For select type
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            // Unique name per company
            $table->unique(['company_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
