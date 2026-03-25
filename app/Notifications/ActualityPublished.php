<?php

namespace App\Notifications;

use App\Models\Actuality;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ActualityPublished extends Notification
{
    use Queueable;

    public function __construct(public Actuality $actuality) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Nouvelle actualité : ' . $this->actuality->title,
            'url'     => route('actualities'),
        ];
    }
}
