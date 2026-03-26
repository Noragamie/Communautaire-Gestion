<?php

namespace App\Mail;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ModificationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Profile $profile, public string $motif) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre modification de profil a été refusée');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.modification-rejected');
    }
}
