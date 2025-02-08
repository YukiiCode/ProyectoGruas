<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos'; // Especifica la tabla personalizada

    protected $fillable = [
        'matricula',
        'marca',
        'modelo',
        'color',
        'tipovehiculo',
        'estado',
    ];
}
