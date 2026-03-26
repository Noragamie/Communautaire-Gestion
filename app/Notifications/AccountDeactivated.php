<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountDeactivated extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Votre compte a été désactivé par un administrateur. Votre profil n\'est plus visible publiquement. Contactez l\'administration pour plus d\'informations.',
            'url'     => route('operator.profile.show'),
        ];
    }
}
