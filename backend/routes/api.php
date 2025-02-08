<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\RetiradaController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('vehiculos', VehiculoController::class);
    Route::apiResource('retiradas', RetiradaController::class);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);