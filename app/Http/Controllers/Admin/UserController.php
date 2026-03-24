<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggleActive(User $user)
    {
        try {
            $user->update(['is_active' => !$user->is_active]);
            return back()->with('success', 'Statut du compte modifié.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification du statut.']);
        }
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de supprimer un administrateur.']);
        }
        try {
            $user->delete();
            return back()->with('success', 'Utilisateur supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    public function authLogs(User $user)
    {
        $logs = $user->authLogs()->latest()->paginate(30);
        return view('admin.users.logs', compact('user','logs'));
    }
}
