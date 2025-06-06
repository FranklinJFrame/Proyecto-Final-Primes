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
        // Forzar HTTPS solo si se detecta que la app corre detrás de proxy
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            $url = 'https://proyecto-final-primes-production-96c3.up.railway.app';

            Config::set('app.url', $url);
            Config::set('app.asset_url', $url);

            // Configurar URLs de Filament (esto sí se puede hacer aquí)
            Config::set('filament.asset_url', $url);
            Config::set('filament.domain', parse_url($url, PHP_URL_HOST));
            Config::set('filament.path', 'admin');

            // Configurar URL pública del filesystem
            Config::set('filesystems.disks.public.url', $url . '/storage');
        }
    }
}
