<?php

namespace App\Mail;

use App\Models\Actuality;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Actuality $actuality,
        public string $unsubscribeToken
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Actualité: '.$this->actuality->title);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.newsletter');
    }
}
