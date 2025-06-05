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
        // Forzar HTTPS en todos los entornos
        URL::forceScheme('https');

        // Configurar Livewire para usar HTTPS
        $appUrl = config('app.url');
        Config::set('livewire.asset_url', $appUrl);
        Config::set('livewire.app_url', $appUrl);
        Config::set('livewire.middleware_group', 'web');
        Config::set('livewire.temporary_file_upload', [
            'disk' => 'local',
            'rules' => ['required', 'file', 'max:12288'],
            'directory' => 'livewire-tmp',
            'middleware' => 'throttle:60,1',
            'preview_mimes' => [
                'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
                'mov', 'avi', 'wmv', 'mp3', 'm4a',
                'jpg', 'jpeg', 'mpga', 'webp', 'wma',
            ],
            'max_upload_time' => 5,
        ]);

        if ($this->app->environment('production')) {
            $url = 'https://proyecto-final-primes-production-96c3.up.railway.app';

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
