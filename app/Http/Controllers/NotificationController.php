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

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function data()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->latest()->take(8)->get()->map(fn($n) => [
            'id'      => $n->id,
            'message' => $n->data['message'] ?? '',
            'motif'   => $n->data['motif'] ?? null,
            'url'     => route('notifications.read', $n->id),
            'read'    => !is_null($n->read_at),
            'date'    => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'count'         => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }
}
