<?php

namespace App\Mail;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ticket $ticket)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre commande #'.$this->ticket->id.' est prête',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-ready',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('receipt', ['ticket' => $this->ticket]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'recu-commande-'.$this->ticket->id.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}