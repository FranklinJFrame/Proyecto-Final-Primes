<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class CustomVerifyEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        try {
            if (static::$createUrlCallback) {
                return call_user_func(static::$createUrlCallback, $notifiable);
            }

            $baseUrl = config('app.url', 'https://proyecto-final-primes-production-96c3.up.railway.app');
            
            // Generate the signed URL
            $temporarySignedURL = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            // Extract the path and query from the signed URL
            $parsedUrl = parse_url($temporarySignedURL);
            $path = $parsedUrl['path'] ?? '';
            $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

            // Construct the full URL using the base URL
            $verificationUrl = rtrim($baseUrl, '/') . $path . $query;

            Log::info('Generated verification URL', [
                'user_id' => $notifiable->getKey(),
                'url' => $verificationUrl
            ]);

            return $verificationUrl;

        } catch (\Exception $e) {
            Log::error('Error generating verification URL: ' . $e->getMessage(), [
                'user_id' => $notifiable->getKey(),
                'exception' => $e
            ]);
            throw $e;
        }
    }
}