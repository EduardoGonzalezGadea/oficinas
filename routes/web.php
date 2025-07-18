<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {

    // CAMBIO: Definimos la ruta del panel como un componente de Volt
    Volt::route('panel', 'dashboard')
        ->name('dashboard');

    // La ruta del perfil también debería ser un componente de Volt
    Volt::route('perfil', 'profile')
        ->name('profile');

    // Generación dinámica de rutas de módulos (esto ya estaba bien)
    $modules = config('modules', []);

    foreach ($modules as $key => $details) {
        Volt::route($key, 'modules.index')
            ->name("{$key}.index")
            ->middleware(['permission:ver-modulo-' . $key])
            ->lazy(fn() => [
                'module_name' => $details['display_name'] ?? $details['name']
            ]);
    }
});

require __DIR__ . '/auth.php';
