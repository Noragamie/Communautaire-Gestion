<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountDeactivated;
use App\Services\ActivityLogger;
use App\Services\CommuneContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filterCommuneId = $request->filled('commune_id') ? (int) $request->commune_id : null;
        $ids = CommuneContext::scopedCommuneIdsForQuery($filterCommuneId);
        $query = User::with(['profile', 'commune']);

        if (CommuneContext::needsTerritorialScope()) {
            if ($ids === []) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('commune_id', $ids);
            }
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        $user = $request->user();
        $showCommuneFilter = CommuneContext::shouldShowAdminCommuneListFilter();
        $managedCommunesForFilter = $showCommuneFilter
            ? $user->managedCommunes()->orderBy('name')->get()
            : collect();

        return view('admin.users.index', compact(
            'users',
            'showCommuneFilter',
            'managedCommunesForFilter'
        ));
    }

    public function createAgent()
    {
        $user = Auth::user();
        $communes = $user->managedCommunes()->orderBy('name')->get();
        if ($communes->isEmpty()) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'Aucune commune assignée à votre compte. Importez les communes (php artisan communes:sync) puis associez-les à l’administrateur.']);
        }
        $defaultCommuneId = CommuneContext::activeId();

        return view('admin.users.create-agent', compact('communes', 'defaultCommuneId'));
    }

    public function storeAgent(Request $request)
    {
        $admin = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'commune_id' => 'required|exists:communes,id',
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est requis.',
            'commune_id.required' => 'La commune est requise.',
        ]);

        $communeId = (int) $request->input('commune_id');
        if (! $admin->managedCommunes()->where('communes.id', $communeId)->exists()) {
            return back()->withErrors(['commune_id' => 'Vous ne gérez pas cette commune.'])->withInput();
        }

        $agent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent_municipal',
            'commune_id' => $communeId,
            'is_active' => true,
        ]);
        $agent->forceFill(['email_verified_at' => now()])->save();

        ActivityLogger::log('agent_created', 'User', $agent->id, $agent->name);

        return redirect()->route('admin.users.index')->with('success', 'Agent municipal créé. Le compte est actif et l’email est considéré comme vérifié.');
    }

    public function toggleActive(User $user)
    {
        $this->ensureCanManageUser($user);

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de modifier le statut d\'un administrateur.']);
        }

        if (Auth::user()->isAgentMunicipal() && $user->isAgentMunicipal() && $user->id !== Auth::id()) {
            return back()->withErrors(['error' => 'Seul un administrateur peut activer ou désactiver un autre agent.']);
        }

        try {
            $newState = ! $user->is_active;
            $user->update(['is_active' => $newState]);
            ActivityLogger::log($newState ? 'user_activated' : 'user_deactivated', 'User', $user->id, $user->name);
            if (! $newState) {
                $user->notify(new AccountDeactivated);
            }

            return back()->with('success', $newState ? 'Compte activé.' : 'Compte désactivé. L\'utilisateur a été notifié.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification du statut.']);
        }
    }

    public function destroy(User $user)
    {
        $this->ensureCanManageUser($user);

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de supprimer un administrateur.']);
        }

        if (Auth::user()->isAgentMunicipal()) {
            if ($user->isAgentMunicipal()) {
                return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer un autre agent municipal.']);
            }
        }

        try {
            $label = $user->name;
            $id = $user->id;

            $profile = $user->profile()->with('documents')->first();
            if ($profile) {
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }
                foreach ($profile->documents as $document) {
                    Storage::disk('public')->delete($document->path);
                }
            }

            DB::table('notifications')
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $user->id)
                ->delete();

            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();

            $user->delete();

            ActivityLogger::log('user_deleted', 'User', $id, $label);

            return back()->with('success', 'Utilisateur supprimé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    public function toggleSuspend(User $user)
    {
        $this->ensureCanManageUser($user);

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de suspendre un administrateur.']);
        }

        if (Auth::user()->isAgentMunicipal() && $user->isAgentMunicipal()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas suspendre un autre agent municipal.']);
        }

        try {
            $newState = ! $user->is_suspended;
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
        $this->ensureCanManageUser($user);
        if (Auth::user()->isAgentMunicipal() && $user->isAdmin()) {
            abort(403);
        }

        $logs = $user->authLogs()->latest()->paginate(30);

        return view('admin.users.logs', compact('user', 'logs'));
    }

    private function ensureCanManageUser(User $user): void
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        $actor = Auth::user();
        if ($actor->isAgentMunicipal()) {
            $cid = CommuneContext::activeId();
            if (! $actor->canManageCommune($cid)) {
                abort(403);
            }
            if ((int) $user->commune_id !== (int) $cid) {
                abort(403);
            }

            return;
        }

        if ($actor->isAdmin()) {
            CommuneContext::authorizeBackofficeResourceCommune($user->commune_id);

            return;
        }

        abort(403);
    }
}
