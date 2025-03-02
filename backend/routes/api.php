<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\RetiradaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CarQueryController;
use App\Http\Controllers\TarifasController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();    
    });
    
    // Vehiculos routes
    Route::get('/vehiculos', [VehiculoController::class, 'index']);
    Route::post('/vehiculos', [VehiculoController::class, 'store']);
    Route::put('/vehiculos/{id}', [VehiculoController::class, 'update']);
    Route::delete('/vehiculos/{id}', [VehiculoController::class, 'destroy']);

    // Retiradas routes
    Route::get('/retiradas', [RetiradaController::class, 'index']);
    Route::post('/retiradas', [RetiradaController::class, 'store']);
    Route::put('/retiradas/{id}', [RetiradaController::class, 'update']);
    Route::delete('/retiradas/{id}', [RetiradaController::class, 'destroy']);
    Route::get('/retiradas/{id}/pdf', [RetiradaController::class, 'generatePDF']);

    // User management routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    // Car API proxy routes
    Route::get('/car-makes', [CarQueryController::class, 'getMakes']);
    Route::get('/car-models', [CarQueryController::class, 'getModels']);
    
    // Tarifas routes
    Route::get('/tarifas', [TarifasController::class, 'index']);
    
    // Logs routes
    Route::get('/logs', [\App\Http\Controllers\Api\LogController::class, 'index']);
});


