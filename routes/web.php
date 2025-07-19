<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // CAMBIO: Apuntamos la ruta '/panel' al nuevo componente 'panel-principal'.
    // Mantenemos el nombre de la ruta como 'dashboard' para que las redirecciones sigan funcionando.
    Volt::route('panel', 'panel-principal')->name('dashboard');

    // Las otras rutas se quedan como están.
    Volt::route('perfil', 'profile')->name('profile');

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
