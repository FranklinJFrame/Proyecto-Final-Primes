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
            $baseUrl = 'https://proyecto-final-primes-production-96c3.up.railway.app';

            if (static::$createUrlCallback) {
                return call_user_func(static::$createUrlCallback, $notifiable);
            }

            // Generate the signed URL
            $temporarySignedURL = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            // Extract the query string from the signed URL
            $parsedUrl = parse_url($temporarySignedURL);
            $queryString = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

            // Construct the verification URL
            $verificationUrl = $baseUrl . '/email/verify/' . $notifiable->getKey() . '/' . sha1($notifiable->getEmailForVerification()) . $queryString;

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