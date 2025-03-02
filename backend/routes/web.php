<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Home route - redirects to home if authenticated, otherwise to login
Route::get('/', function () {
    return view('app');
});

// All routes should return the main app view for SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

// Login route
Route::get('/login', function () {
    return view('app');
})->name('login');

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Home route
    Route::get('/home', function () {
        return view('app');
    })->name('home');
    
    // All other protected routes
    Route::get('/{any}', function () {
        return view('app');
    })->where('any', '^(?!login$).*$');
});

