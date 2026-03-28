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

        if (! $user->commune_id) {
            return view('operator.announcements.index', [
                'announcements' => collect(),
                'locked' => false,
                'no_commune' => true,
                'commune' => null,
            ]);
        }

        $announcements = Announcement::published()
            ->where('commune_id', $user->commune_id)
            ->with('author')
            ->paginate(10)
            ->withQueryString();

        return view('operator.announcements.index', compact('announcements', 'commune'));
    }
}
