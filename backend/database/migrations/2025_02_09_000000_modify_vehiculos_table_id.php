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
        Schema::table('vehiculos', function (Blueprint $table) {
            // Ensure the ID column is properly set as auto-incrementing integer
            if (!Schema::hasColumn('vehiculos', 'id')) {
                $table->id();
            }
        });

        // Update foreign key in retiradas table to ensure proper constraint
        Schema::table('retiradas', function (Blueprint $table) {
            $table->foreign('vehiculo_id')
                  ->references('id')
                  ->on('vehiculos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->id();
        });
    }
};