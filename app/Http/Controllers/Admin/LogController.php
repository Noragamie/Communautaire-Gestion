<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AuthLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'activity');

        // --- Activity logs ---
        $activityQuery = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $activityQuery->where('action', $request->action);
        }
        if ($request->filled('role')) {
            $activityQuery->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $activityQuery->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%")
                                                          ->orWhere('email', 'like', "%$s%"));
        }
        if ($request->filled('date_from')) {
            $activityQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $activityQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $activityQuery->paginate(30, ['*'], 'activity_page')->withQueryString();

        $activityCounts = [
            'total'    => ActivityLog::count(),
            'admin'    => ActivityLog::where('role', 'admin')->count(),
            'operator' => ActivityLog::where('role', 'operateur')->count(),
        ];

        // --- Auth logs ---
        $authQuery = AuthLog::with('user')->latest();

        if ($request->filled('auth_action')) {
            $authQuery->where('action', $request->auth_action);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $authQuery->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%")
                                                      ->orWhere('email', 'like', "%$s%"));
        }
        if ($request->filled('date_from')) {
            $authQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $authQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $authLogs = $authQuery->paginate(30, ['*'], 'auth_page')->withQueryString();

        $authCounts = [
            'total'  => AuthLog::count(),
            'login'  => AuthLog::where('action', 'login')->count(),
            'logout' => AuthLog::where('action', 'logout')->count(),
            'failed' => AuthLog::where('action', 'failed')->count(),
        ];

        return view('admin.logs.index', compact(
            'tab', 'activityLogs', 'activityCounts', 'authLogs', 'authCounts'
        ));
    }
}
