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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechaentrada')->nullable();
            $table->dateTime('fechasalida')->nullable();
            $table->string('lugar')->nullable();
            $table->string('direccion')->nullable();
            $table->string('agente')->nullable();
            $table->string('matricula')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->string('color')->nullable();
            $table->string('tipovehiculo');
            $table->enum('estado', ['disponible', 'retirado', 'en_proceso', 'en_deposito']);
            $table->text('motivo')->nullable();
            $table->string('grua')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
