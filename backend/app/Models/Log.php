<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $fillable = ['usuario_id', 'accion', 'detalles', 'fecha_hora'];
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public static function registrarAccion($usuarioId, $accion, $detalles = null)
    {
        return self::create([
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'detalles' => $detalles
        ]);
    }
}