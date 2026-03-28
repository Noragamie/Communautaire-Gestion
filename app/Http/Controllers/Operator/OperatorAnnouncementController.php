<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class OperatorAnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->loadMissing('commune');
        $commune = $user->commune;

        $profile = $user->profile;

        if (! $profile || $profile->status !== 'approved') {
            return view('operator.announcements.index', [
                'announcements' => collect(),
                'locked' => true,
                'commune' => $commune,
            ]);
        }

        $announcements = Announcement::published()
            ->with(['author', 'commune'])
            ->paginate(10)
            ->withQueryString();

        return view('operator.announcements.index', compact('announcements', 'commune'));
    }

    public function show(Announcement $announcement)
    {
        $user = Auth::user();
        $user->loadMissing('profile');

        if (! $user->profile || $user->profile->status !== 'approved') {
            abort(403);
        }

        if (! $announcement->is_published) {
            abort(404);
        }

        $announcement->load(['author', 'commune']);

        return view('operator.announcements.show', compact('announcement'));
    }
}
