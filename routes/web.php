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

    Route::view('panel', 'dashboard')->name('dashboard');
    Route::view('perfil', 'profile')->name('profile');

    $modules = config('modules', []);

    foreach ($modules as $key => $details) {
        // CAMBIO: La ruta del componente ahora refleja la nueva ubicación
        Volt::route($key, 'modules.index') // <-- 'pages' ha sido eliminado del nombre
            ->name("{$key}.index")
            ->middleware(['permission:ver-modulo-' . $key])
            ->lazy(fn() => [
                'module_name' => $details['display_name'] ?? $details['name']
            ]);
    }
});

require __DIR__ . '/auth.php';
