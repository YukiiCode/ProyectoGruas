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
        Schema::create('retiradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos');
            $table->string('nombre');
            $table->string('nif');
            $table->string('domicilio');
            $table->string('poblacion');
            $table->string('provincia');
            $table->string('permiso');
            $table->date('fecha');
            $table->string('agente');
            $table->decimal('importeretirada', 8, 2);
            $table->decimal('importedeposito', 8, 2);
            $table->decimal('total', 8, 2);
            $table->string('opcionespago');
            $table->string('grua');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retiradas');
    }
};
