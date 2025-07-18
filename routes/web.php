<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

// Agrupamos todas las rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas principales traducidas
    Route::view('panel', 'dashboard')
        ->name('dashboard');

    Route::view('perfil', 'profile')
        ->name('profile');

    // Generación dinámica de rutas de módulos
    $modules = config('modules', []);

    foreach ($modules as $key => $details) {
        Route::prefix($key)->name("$key.")->group(function () use ($key, $details) {

            // Cada módulo tendrá una vista de bienvenida/índice
            // Protegida por el permiso correspondiente
            Route::get('/', function () use ($key, $details) {
                // Aquí necesitarás crear esta vista, ej: /resources/views/pages/modules/index.blade.php
                return view('pages.modules.index', [
                    'module_name' => $details['display_name'] ?? $details['name']
                ]);
            })->middleware(['permission:ver-modulo-' . $key])->name('index');

            // Aquí podrás añadir más rutas para cada módulo en el futuro
            // ej: Route::get('/reportes', ...)->name('reports');
        });
    }
});

// RUTA DE PRUEBA
Volt::route('/test-contador', 'pages.test-contador');

require __DIR__ . '/auth.php';
