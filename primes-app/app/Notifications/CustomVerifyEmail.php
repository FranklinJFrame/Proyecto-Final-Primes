<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        $baseUrl = 'https://proyecto-final-primes-production-96c3.up.railway.app';

        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        $temporarySignedURL = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Extract the query parameters from the signed URL
        $parsedUrl = parse_url($temporarySignedURL);
        $queryParams = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        // Construct the new URL with the production base URL
        return $baseUrl . '/email/verify/' . $notifiable->getKey() . '/' . sha1($notifiable->getEmailForVerification()) . $queryParams;
    }
}