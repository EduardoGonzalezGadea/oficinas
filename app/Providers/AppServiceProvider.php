<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt; // <-- Importar la clase Volt

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // CAMBIO: Añadir esta configuración para Volt
        // Esto le dice a Volt que busque componentes de página no solo en
        // 'resources/views/livewire/pages' sino también en 'resources/views/pages'.
        Volt::mount([
            resource_path('views/livewire/pages'),
            resource_path('views/pages'), // <-- Esta línea soluciona el problema
        ]);
    }
}
