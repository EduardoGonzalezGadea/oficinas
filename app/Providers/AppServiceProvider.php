<?php
namespace App\Providers;
use Livewire\Volt\Volt;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Esto le dice a Volt que busque los archivos de página
        // en la raíz de 'views'.
        Volt::mount(resource_path('views'));
    }
}