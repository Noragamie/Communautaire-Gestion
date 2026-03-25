<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class OperatorAnnouncementController extends Controller
{
    public function index()
    {
        // Seuls les opérateurs avec un profil approuvé peuvent voir les annonces
        $profile = Auth::user()->profile;

        if (!$profile || $profile->status !== 'approved') {
            return view('operator.announcements.index', [
                'announcements' => collect(),
                'locked'        => true,
            ]);
        }

        $announcements = Announcement::published()->paginate(10);

        return view('operator.announcements.index', compact('announcements'));
    }
}
