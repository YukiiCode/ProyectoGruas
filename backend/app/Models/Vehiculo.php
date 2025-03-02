<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehiculos';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fechaentrada',
        'fechasalida',
        'lugar',
        'direccion',
        'agente',
        'matricula',
        'marca',
        'modelo',
        'color',
        'tipovehiculo',
        'estado',
        'motivo',
        'grua'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fechaentrada' => 'datetime',
        'fechasalida' => 'datetime',
    ];
}
