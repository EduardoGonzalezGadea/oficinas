<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // CAMBIO: La ruta del panel vuelve a ser un Route::view.
    // Le decimos: "Para la URL '/panel', muestra la vista 'dashboard'".
    Route::view('panel', 'dashboard')->name('dashboard');

    // Las rutas de perfil y módulos SÍ son componentes de Volt y se quedan como están.
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
