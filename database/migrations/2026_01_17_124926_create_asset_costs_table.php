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
        Schema::create('asset_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->enum('cost_type', ['maintenance', 'repair', 'insurance', 'spare_parts', 'upgrade', 'other']);
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->date('date');
            $table->string('invoice_number')->nullable();
            $table->string('vendor')->nullable();
            $table->string('document_path')->nullable(); // Para guardar facturas/comprobantes
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['asset_id', 'cost_type']);
            $table->index(['asset_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_costs');
    }
};
