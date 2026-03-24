<?php

namespace App\Mail;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Profile $profile) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Félicitations — Votre profil a été approuvé !');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.profile-approved');
    }
}
