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
        // Le decimos a Volt que busque componentes en la raíz de 'views' ADEMÁS de en 'livewire/pages'
        Volt::mount(resource_path('views'));
    }
}
