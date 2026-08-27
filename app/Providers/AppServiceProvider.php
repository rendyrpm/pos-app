<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS URLs when APP_URL uses HTTPS (untuk reverse proxy)
        if (config('app.url') && str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
    }
}
