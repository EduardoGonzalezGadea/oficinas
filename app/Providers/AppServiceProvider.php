<?php
namespace App\Providers;
use Livewire\Volt\Volt;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fuerza la URL base en desarrollo local
        if ($this->app->environment('local')) {
            URL::forceRootUrl(config('app.url'));
        }

        // Sobrescribe la ruta de assets de Livewire Tables
        $this->app->bind('livewire-tables.asset.url', function () {
            return config('app.url') . '/vendor/livewire-tables';
        });

        // Esto le dice a Volt que busque los archivos de página
        // en la raíz de 'views'.
        Volt::mount(resource_path('views'));

    }
}