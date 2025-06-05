<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment('production')) {
            $url = 'https://proyecto-final-primes-production-96c3.up.railway.app';

            // Forzar HTTPS
            URL::forceScheme('https');

            // Establecer URL base
            Config::set('app.url', $url);
            Config::set('app.asset_url', $url);
            Config::set('filament.asset_url', $url);
            Config::set('filament.domain', parse_url($url, PHP_URL_HOST));

            // Configurar URL pública del filesystem
            Config::set('filesystems.disks.public.url', $url . '/storage');
        }
    }
}
