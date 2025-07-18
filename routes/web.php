<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('panel', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    // Aquí irán las rutas de los módulos
    Route::prefix('jefatura')->name('jefatura.')->group(function () {
        Route::get('/', function () {
            return view('modules.jefatura.index');
        })->name('index');
    });

    Route::prefix('tesoreria')->name('tesoreria.')->group(function () {
        Route::get('/', function () {
            return view('modules.tesoreria.index');
        })->name('index');
    });

    Route::prefix('dca')->name('dca.')->group(function () {
        Route::get('/', function () {
            return view('modules.dca.index');
        })->name('index');
    });

    // ... etc para los demás módulos
});

require __DIR__ . '/auth.php';
