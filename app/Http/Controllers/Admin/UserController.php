<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountDeactivated;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            $newState = !$user->is_active;
            $user->update(['is_active' => $newState]);
            ActivityLogger::log($newState ? 'user_activated' : 'user_deactivated', 'User', $user->id, $user->name);
            if (!$newState) {
                $user->notify(new AccountDeactivated());
            }
            return back()->with('success', $newState ? 'Compte activé.' : 'Compte désactivé. L\'utilisateur a été notifié.');
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
            $label = $user->name;
            $id    = $user->id;

            // 1. Nettoyer les fichiers physiques (photo + documents)
            $profile = $user->profile()->with('documents')->first();
            if ($profile) {
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }
                foreach ($profile->documents as $document) {
                    Storage::disk('public')->delete($document->path);
                }
            }

            // 2. Supprimer les notifications orphelines
            DB::table('notifications')
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $user->id)
                ->delete();

            // 3. Invalider toutes les sessions actives de l'utilisateur
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();

            // 4. Supprimer l'utilisateur (cascade : profil, documents DB, auth_logs)
            $user->delete();

            ActivityLogger::log('user_deleted', 'User', $id, $label);
            return back()->with('success', 'Utilisateur supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    public function toggleSuspend(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de suspendre un administrateur.']);
        }
        try {
            $newState = !$user->is_suspended;
            $user->update(['is_suspended' => $newState]);
            if ($newState) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            ActivityLogger::log($newState ? 'user_suspended' : 'user_unsuspended', 'User', $user->id, $user->name);
            $message = $newState ? 'Compte suspendu.' : 'Suspension levée.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification du statut.']);
        }
    }

    public function authLogs(User $user)
    {
        $logs = $user->authLogs()->latest()->paginate(30);
        return view('admin.users.logs', compact('user','logs'));
    }
}
