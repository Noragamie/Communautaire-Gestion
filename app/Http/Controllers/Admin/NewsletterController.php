<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function index()
    {
        $stats = [
            'total'       => Newsletter::count(),
            'actifs'      => Newsletter::where('subscribed', true)->count(),
            'desabonnes'  => Newsletter::where('subscribed', false)->count(),
            'avec_compte' => Newsletter::where('subscribed', true)->whereNotNull('user_id')->count(),
            'anonymes'    => Newsletter::where('subscribed', true)->whereNull('user_id')->count(),
            'ce_mois'     => Newsletter::where('subscribed', true)
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count(),
        ];

        $subscribers = Newsletter::with('user:id,name')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.newsletter', compact('stats', 'subscribers'));
    }
}
