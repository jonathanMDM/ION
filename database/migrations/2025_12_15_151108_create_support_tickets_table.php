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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Cliente que reportó
            $table->foreignId('superadmin_id')->nullable()->constrained('users')->onDelete('set null'); // Superadmin que atiende
            $table->enum('contact_type', ['call', 'whatsapp', 'email', 'other'])->default('call');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('category', ['technical', 'configuration', 'query', 'error', 'other'])->default('technical');
            $table->string('subject');
            $table->text('description');
            $table->text('solution')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->integer('estimated_time')->nullable()->comment('Tiempo estimado en minutos');
            $table->integer('actual_time')->nullable()->comment('Tiempo real en minutos');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index('ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
