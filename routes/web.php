<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

// Agrupamos todas las rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas principales
    Route::view('panel', 'dashboard')->name('dashboard');
    Route::view('perfil', 'profile')->name('profile');

    // Generación dinámica de rutas de módulos
    $modules = config('modules', []);

    foreach ($modules as $key => $details) {
        // Usamos Volt::route() para registrar la ruta del componente
        Volt::route($key, 'pages.modules.index')
            ->name("{$key}.index")
            ->middleware(['permission:ver-modulo-' . $key])
            // Pasamos los datos al componente con el método lazy()
            ->lazy(fn() => [
                'module_name' => $details['display_name'] ?? $details['name']
            ]);
    }
});

require __DIR__ . '/auth.php';
