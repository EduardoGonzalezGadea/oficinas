<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Esto ya funciona. Llama a 'panel-principal'.
    Volt::route('panel', 'panel-principal')->name('dashboard');

    // CAMBIO: Apuntamos la ruta '/perfil' al componente 'profile'.
    Volt::route('perfil', 'profile')->name('profile');

    // CAMBIO: Apuntamos las rutas de módulos al componente 'modules-index'.
    $modules = config('modules', []);
    foreach ($modules as $key => $details) {
        Volt::route($key, 'modules-index') // Apuntamos al nuevo archivo
            ->name("{$key}.index")
            ->middleware(['permission:ver-modulo-' . $key])
            ->lazy(fn() => [
                'module_name' => $details['display_name'] ?? $details['name']
            ]);
    }
});

require __DIR__ . '/auth.php';
