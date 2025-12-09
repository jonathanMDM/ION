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
        Schema::create('field_visibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('field_key'); // 'municipality_plate' or 'custom_1' (id of custom field)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('role')->nullable(); // 'admin', 'editor', 'viewer'
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            // Index for faster lookups
            $table->index(['company_id', 'field_key', 'user_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_visibilities');
    }
};
