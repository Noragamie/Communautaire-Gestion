<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Marque une notification comme lue et redirige vers son URL.
     */
    public function read(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;
        return $url ? redirect($url) : back();
    }

    /**
     * Marque toutes les notifications comme lues.
     */
    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
