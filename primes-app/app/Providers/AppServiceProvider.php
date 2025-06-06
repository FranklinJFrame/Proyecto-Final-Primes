<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        URL::macro('alternateHasCorrectSignature',
        function (Request $request, $absolute = true, array $ignoreQuery = []) {
            $ignoreQuery[] = 'signature';

            $absoluteUrl = url($request->path());
            $url = $absolute ? $absoluteUrl : '/' . $request->path();

            $queryString = collect(explode('&', (string) $request
                ->server->get('QUERY_STRING')))
                ->reject(fn($parameter) => in_array(Str::before($parameter, '='), $ignoreQuery))
                ->join('&');

            $original = rtrim($url . '?' . $queryString, '?');

            // Use the application key as the HMAC key
            $key = config('app.key'); // Ensure app.key is properly set in .env

            if (empty($key)) {
                throw new \RuntimeException('Application key is not set.');
            }

            $signature = hash_hmac('sha256', $original, $key);
            return hash_equals($signature, (string) $request->query('signature', ''));
        });

        URL::macro('alternateHasValidSignature', function (Request $request, $absolute = true, array $ignoreQuery = []) {
            return URL::alternateHasCorrectSignature($request, $absolute, $ignoreQuery)
                && URL::signatureHasNotExpired($request);
        });

        Request::macro('hasValidSignature', function ($absolute = true, array $ignoreQuery = []) {
            return URL::alternateHasValidSignature($this, $absolute, $ignoreQuery);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS en todos los entornos
        URL::forceScheme('https');

        if ($this->app->environment('production')) {
            $url = 'https://proyecto-final-primes-production-96c3.up.railway.app';

            // Establecer URL base
            Config::set('app.url', $url);
            Config::set('app.asset_url', $url);
            
            // Configurar URLs de Filament
            Config::set('filament.asset_url', $url);
            Config::set('filament.domain', parse_url($url, PHP_URL_HOST));
            Config::set('filament.path', 'admin');
            Config::set('filament.auth.guard', 'web');
            Config::set('filament.middleware.auth', ['web', 'auth']);
            Config::set('filament.middleware.base', ['web']);
            
            // Configurar URLs de Livewire
            Config::set('livewire.asset_url', $url);
            Config::set('livewire.app_url', $url);
            Config::set('livewire.middleware_group', ['web', 'admin']);
            Config::set('livewire.temporary_file_upload', [
                'disk' => 'public',
                'rules' => ['required', 'file', 'max:12288'],
                'directory' => 'livewire-tmp',
                'middleware' => ['web'],
                'preview_mimes' => [
                    'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
                    'mov', 'avi', 'wmv', 'mp3', 'm4a',
                    'jpg', 'jpeg', 'mpga', 'webp', 'wma',
                ],
                'max_upload_time' => 5,
            ]);

            // Configurar URL pública del filesystem
            Config::set('filesystems.disks.public.url', $url . '/storage');
        }
    }
}
