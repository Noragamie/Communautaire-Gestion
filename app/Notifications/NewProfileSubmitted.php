<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProfileSubmitted extends Notification
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
            'message'   => "Nouveau profil soumis par {$this->profile->user->name}",
            'user_name' => $this->profile->user->name,
            'profile_id'=> $this->profile->id,
            'url'       => route('admin.profiles.show', $this->profile),
        ];
    }
}
