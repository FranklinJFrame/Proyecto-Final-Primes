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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
            config(['app.url' => 'https://proyecto-final-primes-production-96c3.up.railway.app']);
        }
        
        $url = 'https://proyecto-final-primes-production-96c3.up.railway.app';
        
        // Forzar HTTPS y URL base
        URL::forceRootUrl($url);
        
        // Forzar configuración de assets
        Config::set('app.url', $url);
        Config::set('app.asset_url', $url);
        Config::set('filament.asset_url', $url);
        Config::set('filament.domain', parse_url($url, PHP_URL_HOST));
        
        // Forzar configuración de filesystem
        Config::set('filesystems.disks.public.url', $url.'/storage');
    }
}