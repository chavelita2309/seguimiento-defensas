<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class AvanceSubidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $postulante;
    public $fechaEntrega;
    public $fechaLimite;
    public $archivoPath;

    /**
     * Create a new message instance.
     */
    public function __construct($postulante, $fechaEntrega, $fechaLimite, $archivoPath)
    {
        $this->postulante = $postulante;
        $this->fechaEntrega = $fechaEntrega;
        $this->fechaLimite = $fechaLimite;
        $this->archivoPath = $archivoPath;
    }

    /**
     * Define el asunto del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo avance de ' . $this->postulante->nombre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.avance_subido',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
         return [
            Attachment::fromPath(storage_path('app/' . $this->archivoPath))
                ->as('avance_' . $this->postulante->nombre . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
    
}
