<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Estas rutas usan el patrón que funciona
    Volt::route('panel', 'panel-principal')->name('panel');
    Volt::route('perfil', 'profile')->name('profile');

    // CAMBIO: Aplicamos exactamente el mismo patrón a los módulos.
    $modules = config('modules', []);
    foreach ($modules as $key => $details) {
        Volt::route($key, 'modules-index') // Llama al componente 'modules-index'
            ->name("{$key}.index")
            ->middleware(['permission:ver-modulo-' . $key])
            // Usamos ->lazy() de nuevo. Ahora que la configuración es estable,
            // debería funcionar correctamente.
            ->lazy(fn() => [
                'module_name' => $details['display_name'] ?? $details['name']
            ]);
    }
});

require __DIR__ . '/auth.php';
