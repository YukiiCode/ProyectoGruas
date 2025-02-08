<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios'; // Especifica la tabla personalizada

    protected $fillable = [
        'nombre', 'usuario', 'password', 'rol',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}