<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retirada extends Model
{
    protected $table = 'retiradas';

    protected $fillable = [
        'vehiculo_id',
        'fecha',
        'nombre',
        'nif',
        'domicilio',
        'poblacion',
        'provincia',
        'permiso',
        'agente',
        'importeretirada',
        'importedeposito',
        'total',
        'opcionespago',
        'grua',
    ];
    
    public $timestamps = false;
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}
