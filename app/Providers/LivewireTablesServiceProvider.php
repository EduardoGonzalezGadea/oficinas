<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LivewireTablesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('livewire-tables.asset.url', function () {
            return config('app.url');
        });
    }
}
