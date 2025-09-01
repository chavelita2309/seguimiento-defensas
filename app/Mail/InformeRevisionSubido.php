<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\RevisionAvance;

class InformeRevisionSubido extends Mailable
{
    use Queueable, SerializesModels;

    public $revision;
    public $avance;
    public $postulante;
    public $tutor;


    /**
     * Create a new message instance.
     */
    public function __construct( RevisionAvance $revision )
    {
        //
        $this->revision   = $revision;
        $this->avance     = $revision->avance;
        $this->postulante = $this->avance->proyecto->postulante->user ?? null;
        $this->tutor      = $this->avance->proyecto->tutor->user ?? null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informe de Revisión Subido',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.informes.subido',
             with: [
                'revision'   => $this->revision,
                'avance'     => $this->avance,
                'postulante' => $this->postulante,
                'tutor'      => $this->tutor,
            ]
        );

        
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->revision->informe_path) {
            return [
                Attachment::fromPath(storage_path('app/public/' . $this->revision->informe_path))
                          ->as('informe_revision.pdf')
                          ->withMime('application/pdf'),
            ];
        }

        return [];
    
    }
}
