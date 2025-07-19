<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AssetUrlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('livewire-tables.asset.url', function () {
            return config('app.url') . '/vendor/rappasoft/laravel-livewire-tables';
        });
    }
}
