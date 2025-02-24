<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\VehiculoController;
use App\Http\Controllers\Api\RetiradaController;
use App\Http\Controllers\Api\UserController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protected routes

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


