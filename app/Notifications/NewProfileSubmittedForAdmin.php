<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProfileSubmittedForAdmin extends Notification
{
    use Queueable;

    public function __construct(public Profile $profile) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->profile->loadMissing('user.commune');
        $user = $this->profile->user;
        $communeName = $user->commune?->name ?? 'Commune non renseignée';

        return [
            'message' => "Nouveau profil soumis par {$user->name} — {$communeName}",
            'user_name' => $user->name,
            'commune_name' => $communeName,
            'profile_id' => $this->profile->id,
            'url' => route('admin.profiles.show', $this->profile),
        ];
    }
}
