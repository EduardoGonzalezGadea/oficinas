<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', UserController::class);
    
    Volt::route('panel', 'panel-principal')->name('panel');
    Volt::route('perfil', 'profile')->name('profile');

    $modules = config('modules', []);
    foreach ($modules as $key => $details) {
        Volt::route($key, 'modules-index')
            ->name("{$key}.index")
            ->middleware(['permission:ver-modulo-' . $key])
            ->lazy(fn() => [
                'module_name' => $details['display_name'] ?? $details['name']
            ]);
    }
});

require __DIR__ . '/auth.php';
