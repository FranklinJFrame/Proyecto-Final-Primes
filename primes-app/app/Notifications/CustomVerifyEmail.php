<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        $productionUrl = 'https://proyecto-final-primes-production-96c3.up.railway.app';

        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Replace localhost URL with production URL
        return str_replace(['http://127.0.0.1:8000', 'http://localhost:8000'], $productionUrl, $url);
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        return (new MailMessage)
            ->subject('Verifica tu correo en TECNOBOX')
            ->view('emails.verify-email-tecnobox', [
                'url' => $verificationUrl,
                'email' => $notifiable->email,
            ]);
    }
}