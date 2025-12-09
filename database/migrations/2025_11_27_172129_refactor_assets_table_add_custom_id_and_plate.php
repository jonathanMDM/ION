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
            $table->string('custom_id')->nullable()->unique()->after('id');
            $table->string('municipality_plate')->nullable()->after('custom_id');
            $table->dropColumn('characteristics');
            $table->dropColumn('has_municipality_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('custom_id');
            $table->dropColumn('municipality_plate');
            $table->text('characteristics')->nullable();
            $table->boolean('has_municipality_plate')->default(false);
        });
    }
};
