<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementPublished extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $url = match ($notifiable->role) {
            'admin' => route('admin.announcements.edit', $this->announcement),
            'operateur' => route('operator.announcements.show', $this->announcement),
            default => url('/'),
        };

        return [
            'message' => 'Nouvelle annonce : ' . $this->announcement->title,
            'url'     => $url,
        ];
    }
}
