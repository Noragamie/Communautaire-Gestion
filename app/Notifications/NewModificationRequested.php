<?php

namespace App\Notifications;

use App\Models\ModificationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewModificationRequested extends Notification
{
    use Queueable;

    public function __construct(public ModificationRequest $modRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "{$this->modRequest->profile->user->name} a fait une demande de modification",
            'url'     => route('admin.modifications.show', $this->modRequest),
        ];
    }
}
