<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/reset?token=' . $this->token . '&email=' . urlencode($this->email));
        return (new MailMessage)
            ->subject('Restablece tu contraseña en TECNOBOX')
            ->view('emails.reset-password-tecnobox', [
                'url' => $url,
                'email' => $this->email,
                'token' => $this->token,
            ]);
    }
} 