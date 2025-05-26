<?php

namespace App\Mail;

use App\Models\Devolucion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DevolucionEstadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devolucion;
    public $titulo;
    public $mensaje;
    public $replyToEmail;

    public function __construct(Devolucion $devolucion, $titulo, $mensaje, $replyToEmail = null)
    {
        $this->devolucion = $devolucion;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->replyToEmail = $replyToEmail;
    }

    public function build()
    {
        $mail = $this->subject($this->titulo)
            ->view('emails.devolucion-estado');
        if ($this->replyToEmail) {
            $mail->replyTo($this->replyToEmail);
        }
        // Adjuntar imágenes si existen
        if (is_array($this->devolucion->imagenes_adjuntas)) {
            foreach ($this->devolucion->imagenes_adjuntas as $imgPath) {
                $fullPath = storage_path('app/public/' . ltrim($imgPath, '/'));
                if (file_exists($fullPath)) {
                    $mail->attach($fullPath);
                }
            }
        }
        return $mail;
    }
} 