<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\RetiradaController;


// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/vehiculos', [VehiculoController::class, 'index']);


// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/vehiculos', [VehiculoController::class, 'store']);
    Route::apiResource('retiradas', RetiradaController::class);
});

