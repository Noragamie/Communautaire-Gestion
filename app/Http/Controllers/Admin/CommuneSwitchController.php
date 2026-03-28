<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CommuneContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommuneSwitchController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        if (! $user->isAdmin()) {
            abort(403);
        }

        if ($request->input('commune_id') === 'all') {
            CommuneContext::setViewAllManagedCommunes();

            return back()->with('success', 'Vue : toutes vos communes.');
        }

        $request->validate([
            'commune_id' => 'required|integer|exists:communes,id',
        ], [
            'commune_id.required' => 'Choisissez une commune.',
            'commune_id.exists' => 'Commune invalide.',
        ]);

        $id = (int) $request->input('commune_id');
        if (! $user->managedCommunes()->where('communes.id', $id)->exists()) {
            return back()->withErrors(['commune_id' => 'Vous n\'administrez pas cette commune.']);
        }

        CommuneContext::setActiveCommuneId($id);

        return back()->with('success', 'Commune active mise à jour.');
    }
}
