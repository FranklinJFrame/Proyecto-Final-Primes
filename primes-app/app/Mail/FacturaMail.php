<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Pedidos;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $pdfContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedido, $pdfContent)
    {
        $this->pedido = $pedido;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tu factura de compra en TECNOBOX')
            ->view('emails.factura')
            ->attachData($this->pdfContent, 'factura-' . str_pad($this->pedido->id, 8, '0', STR_PAD_LEFT) . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
} 