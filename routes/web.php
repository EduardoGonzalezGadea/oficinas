<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Volt buscará 'dashboard' en livewire/pages/dashboard.blade.php
    Volt::route('panel', 'dashboard')->name('dashboard');

    // Volt buscará 'profile' en livewire/pages/profile.blade.php
    Volt::route('perfil', 'profile')->name('profile');

    // Volt buscará 'modules.index' en livewire/pages/modules/index.blade.php
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
