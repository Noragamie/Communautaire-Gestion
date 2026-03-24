<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Profile::count(),
            'pending'  => Profile::where('status', 'pending')->count(),
            'approved' => Profile::where('status', 'approved')->count(),
            'rejected' => Profile::where('status', 'rejected')->count(),
        ];

        $recentProfiles = Profile::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProfiles'));
    }
}
