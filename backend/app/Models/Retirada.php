<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retirada extends Model
{
    protected $table = 'retiradas'; // Especifica la tabla personalizada

    protected $fillable = [
        'vehiculo_id',
        'nombre',
        'nif',
        'domicilio',
        'poblacion',
        'provincia',
        'permiso',
        'fecha',
        'agente',
        'importeretirada',
        'importedeposito',
        'total',
        'opcionespago'
    ];
}
