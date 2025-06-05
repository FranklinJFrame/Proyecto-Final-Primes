<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Support\Facades\URL;

class VerifyEmailTecnobox extends LaravelVerifyEmail
{
    use Queueable;

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifica tu correo | TECNOBOX')
            ->markdown('emails.verify-email-tecnobox', [
                'url' => URL::temporarySignedRoute(
                    'verification.verify.custom',
                    now()->addMinutes(60),
                    [
                        'id' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification())
                    ]
                ),
                'user' => $notifiable
            ]);
    }
}
