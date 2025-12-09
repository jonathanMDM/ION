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
            $table->integer('user_limit')->default(10)->after('status'); // Límite de usuarios
            $table->date('subscription_expires_at')->nullable()->after('user_limit'); // Fecha de expiración
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['user_limit', 'subscription_expires_at']);
        });
    }
};
