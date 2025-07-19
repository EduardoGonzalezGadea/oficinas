<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Escanea TODOS los directorios de vistas en busca de componentes.
        Volt::mount(resource_path('views'));
    }
}
