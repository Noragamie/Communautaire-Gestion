<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileApproved extends Notification
{
    use Queueable;

    public function __construct(public Profile $profile) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Votre profil a été approuvé ! Il est maintenant visible dans l\'annuaire.',
            'url'     => route('operator.profile.show'),
        ];
    }
}
