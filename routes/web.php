<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// La página de bienvenida puede ser una vista de Blade normal
Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {

    // Ahora Volt encontrará 'dashboard' en livewire/pages/dashboard.blade.php
    Volt::route('panel', 'dashboard')->name('dashboard');

    // Ahora Volt encontrará 'profile' en livewire/pages/profile.blade.php
    Volt::route('perfil', 'profile')->name('profile');

    // La lógica de los módulos ya era correcta para esta estructura
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
