<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ModificationRejected extends Notification
{
    use Queueable;

    public function __construct(public Profile $profile, public string $motif) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Votre demande de modification a été refusée.',
            'motif'   => $this->motif,
            'url'     => route('operator.profile.show'),
        ];
    }
}
