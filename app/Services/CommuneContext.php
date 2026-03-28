<?php

namespace App\Services;

use App\Models\Commune;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommuneContext
{
    public const SESSION_ALL_FLAG = 'admin_view_all_managed_communes';

    /**
     * Commune unique « active » (sidebar, libellés). Null pour l’admin en vue « toutes mes communes ».
     */
    public static function activeId(): ?int
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        if ($user->isAgentMunicipal()) {
            return $user->commune_id;
        }

        if ($user->isAdmin()) {
            if (self::isAdminViewingAllManagedCommunes()) {
                return null;
            }

            $id = Session::get('admin_active_commune_id');
            if ($id && $user->managedCommunes()->where('communes.id', $id)->exists()) {
                return (int) $id;
            }

            $first = $user->managedCommunes()->orderBy('name')->first();
            if ($first) {
                Session::put('admin_active_commune_id', $first->id);
                Session::put(self::SESSION_ALL_FLAG, false);

                return $first->id;
            }

            return null;
        }

        return null;
    }

    public static function activeCommune(): ?Commune
    {
        $id = self::activeId();

        return $id ? Commune::find($id) : null;
    }

    /**
     * Vue agrégée : toutes les communes gérées par l’admin (défaut si aucune préférence en session).
     */
    public static function isAdminViewingAllManagedCommunes(): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return false;
        }

        if (Session::has(self::SESSION_ALL_FLAG)) {
            return (bool) Session::get(self::SESSION_ALL_FLAG);
        }

        $id = Session::get('admin_active_commune_id');
        if ($id && $user->managedCommunes()->where('communes.id', $id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Filtre « par commune » sur les listes : utile seulement en vue « toutes mes communes »
     * avec au moins 2 communes. En commune unique active, le périmètre sidebar suffit.
     */
    public static function shouldShowAdminCommuneListFilter(): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return false;
        }

        return self::isAdminViewingAllManagedCommunes()
            && $user->managedCommunes()->count() > 1;
    }

    /**
     * Identifiants de communes à utiliser dans les requêtes (WHERE IN).
     * Si $filterCommuneId est fourni et autorisé, retourne uniquement celui-ci.
     *
     * @return list<int>
     */
    public static function scopedCommuneIdsForQuery(?int $filterCommuneId = null): array
    {
        $user = Auth::user();
        if (! $user || ! self::needsTerritorialScope()) {
            return [];
        }

        if ($user->isAgentMunicipal()) {
            $ids = $user->commune_id ? [(int) $user->commune_id] : [];
        } else {
            if (self::isAdminViewingAllManagedCommunes()) {
                $ids = $user->managedCommunes()->pluck('communes.id')->map(fn ($id) => (int) $id)->values()->all();
            } else {
                $id = Session::get('admin_active_commune_id');
                if ($id && $user->managedCommunes()->where('communes.id', $id)->exists()) {
                    $ids = [(int) $id];
                } else {
                    $first = $user->managedCommunes()->orderBy('name')->first();
                    if ($first) {
                        Session::put('admin_active_commune_id', $first->id);
                        Session::put(self::SESSION_ALL_FLAG, false);
                        $ids = [(int) $first->id];
                    } else {
                        $ids = [];
                    }
                }
            }
        }

        if ($filterCommuneId !== null && $ids !== [] && in_array($filterCommuneId, $ids, true)) {
            return [$filterCommuneId];
        }

        return $ids;
    }

    public static function setActiveCommuneId(int $communeId): void
    {
        Session::put('admin_active_commune_id', $communeId);
        Session::put(self::SESSION_ALL_FLAG, false);
    }

    public static function setViewAllManagedCommunes(): void
    {
        Session::put(self::SESSION_ALL_FLAG, true);
        Session::forget('admin_active_commune_id');
    }

    public static function userBelongsToActiveCommune(?User $target): bool
    {
        if (! $target || self::activeId() === null) {
            return false;
        }

        return (int) $target->commune_id === (int) self::activeId();
    }

    public static function profileBelongsToActiveCommune(Profile $profile): bool
    {
        return self::userBelongsToActiveCommune($profile->user);
    }

    public static function needsTerritorialScope(): bool
    {
        $user = Auth::user();

        return $user && ($user->isAdmin() || $user->isAgentMunicipal());
    }

    /**
     * Agent : accès uniquement si la ressource est sur sa commune.
     * Admin : accès si la commune est dans son périmètre ; aligne la commune active (session).
     */
    public static function authorizeBackofficeResourceCommune(?int $resourceCommuneId): void
    {
        if (! self::needsTerritorialScope()) {
            return;
        }

        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        if ($user->isAgentMunicipal()) {
            if ($resourceCommuneId === null
                || (int) $resourceCommuneId !== (int) $user->commune_id) {
                abort(403);
            }

            return;
        }

        if ($user->isAdmin()) {
            if ($resourceCommuneId === null || ! $user->canManageCommune((int) $resourceCommuneId)) {
                abort(403);
            }
            self::setActiveCommuneId((int) $resourceCommuneId);

            return;
        }

        abort(403);
    }
}
