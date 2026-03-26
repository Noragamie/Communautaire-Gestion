@extends('layouts.admin')

@section('title', 'Journal des logs')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Journal des logs</h1>
        <p class="text-sm text-gray-500 mt-1">Historique de toutes les actions et connexions</p>
    </div>

    {{-- Onglets --}}
    <div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
        <a href="{{ route('admin.logs', array_merge(request()->except('tab'), ['tab' => 'activity'])) }}"
           class="px-5 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $tab === 'activity' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Actions métier
        </a>
        <a href="{{ route('admin.logs', array_merge(request()->except('tab'), ['tab' => 'auth'])) }}"
           class="px-5 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $tab === 'auth' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Connexions
        </a>
    </div>

    @if($tab === 'activity')
        {{-- Compteurs activity --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total actions</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($activityCounts['total']) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-purple-600 uppercase tracking-wide">Par les admins</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($activityCounts['admin']) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Par les opérateurs</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($activityCounts['operator']) }}</p>
            </div>
        </div>

        {{-- Filtres activity --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
            <form method="GET" action="{{ route('admin.logs') }}" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="tab" value="activity">
                <div class="flex-1 min-w-48">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Utilisateur</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nom ou email..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Rôle</label>
                    <select name="role" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                        <option value="">Tous</option>
                        <option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                        <option value="operateur" {{ request('role') === 'operateur' ? 'selected' : '' }}>Opérateur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Action</label>
                    <select name="action" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                        <option value="">Toutes</option>
                        <optgroup label="Profils">
                            <option value="profile_approved"  {{ request('action') === 'profile_approved'  ? 'selected' : '' }}>Profil approuvé</option>
                            <option value="profile_rejected"  {{ request('action') === 'profile_rejected'  ? 'selected' : '' }}>Profil rejeté</option>
                            <option value="profile_suspended" {{ request('action') === 'profile_suspended' ? 'selected' : '' }}>Profil suspendu</option>
                            <option value="profile_deleted"   {{ request('action') === 'profile_deleted'   ? 'selected' : '' }}>Profil supprimé</option>
                            <option value="profile_created"   {{ request('action') === 'profile_created'   ? 'selected' : '' }}>Profil créé</option>
                            <option value="profile_updated"   {{ request('action') === 'profile_updated'   ? 'selected' : '' }}>Profil modifié</option>
                        </optgroup>
                        <optgroup label="Utilisateurs">
                            <option value="user_activated"   {{ request('action') === 'user_activated'   ? 'selected' : '' }}>Compte activé</option>
                            <option value="user_deactivated" {{ request('action') === 'user_deactivated' ? 'selected' : '' }}>Compte désactivé</option>
                            <option value="user_deleted"     {{ request('action') === 'user_deleted'     ? 'selected' : '' }}>Utilisateur supprimé</option>
                        </optgroup>
                        <optgroup label="Catégories">
                            <option value="category_created" {{ request('action') === 'category_created' ? 'selected' : '' }}>Catégorie créée</option>
                            <option value="category_updated" {{ request('action') === 'category_updated' ? 'selected' : '' }}>Catégorie modifiée</option>
                            <option value="category_deleted" {{ request('action') === 'category_deleted' ? 'selected' : '' }}>Catégorie supprimée</option>
                        </optgroup>
                        <optgroup label="Actualités & Annonces">
                            <option value="actuality_published"    {{ request('action') === 'actuality_published'    ? 'selected' : '' }}>Actualité publiée</option>
                            <option value="announcement_published" {{ request('action') === 'announcement_published' ? 'selected' : '' }}>Annonce publiée</option>
                            <option value="actuality_deleted"      {{ request('action') === 'actuality_deleted'      ? 'selected' : '' }}>Actualité supprimée</option>
                            <option value="announcement_deleted"   {{ request('action') === 'announcement_deleted'   ? 'selected' : '' }}>Annonce supprimée</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Du</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Au</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search','action','role','date_from','date_to']))
                        <a href="{{ route('admin.logs', ['tab' => 'activity']) }}"
                           class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table activity --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date & Heure</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Rôle</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Cible</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activityLogs as $log)
                        @php $color = \App\Models\ActivityLog::actionColor($log->action); @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->user->email }}</p>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Supprimé</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->role === 'admin')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                                @elseif($log->role === 'operateur')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Opérateur</span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($color === 'green') bg-green-100 text-green-800
                                    @elseif($color === 'red') bg-red-100 text-red-800
                                    @elseif($color === 'orange') bg-orange-100 text-orange-800
                                    @elseif($color === 'blue') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-700 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($color === 'green') bg-green-500
                                        @elseif($color === 'red') bg-red-500
                                        @elseif($color === 'orange') bg-orange-500
                                        @elseif($color === 'blue') bg-blue-500
                                        @else bg-gray-400 @endif">
                                    </span>
                                    {{ \App\Models\ActivityLog::actionLabel($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->target_label)
                                    <p class="text-sm text-gray-800">{{ $log->target_label }}</p>
                                    <p class="text-xs text-gray-400">{{ $log->target_type }} #{{ $log->target_id }}</p>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-sm text-gray-400">
                                Aucun log d'action trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($activityLogs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $activityLogs->links() }}
                </div>
            @endif
        </div>

    @else
        {{-- Compteurs auth --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($authCounts['total']) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Connexions</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($authCounts['login']) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Déconnexions</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($authCounts['logout']) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs font-medium text-red-500 uppercase tracking-wide">Échecs</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($authCounts['failed']) }}</p>
            </div>
        </div>

        {{-- Filtres auth --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
            <form method="GET" action="{{ route('admin.logs') }}" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="tab" value="auth">
                <div class="flex-1 min-w-48">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Rechercher un utilisateur</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nom ou email..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Action</label>
                    <select name="auth_action" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                        <option value="">Toutes</option>
                        <option value="login"  {{ request('auth_action') === 'login'  ? 'selected' : '' }}>Connexion</option>
                        <option value="logout" {{ request('auth_action') === 'logout' ? 'selected' : '' }}>Déconnexion</option>
                        <option value="failed" {{ request('auth_action') === 'failed' ? 'selected' : '' }}>Échec</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Du</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Au</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search','auth_action','date_from','date_to']))
                        <a href="{{ route('admin.logs', ['tab' => 'auth']) }}"
                           class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table auth --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date & Heure</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Adresse IP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Navigateur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($authLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->user->email }}</p>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Inconnu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->action === 'login')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Connexion
                                    </span>
                                @elseif($log->action === 'logout')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Déconnexion
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Échec
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $log->user_agent }}">
                                {{ $log->user_agent ? Str::limit($log->user_agent, 60) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-sm text-gray-400">
                                Aucun log de connexion trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($authLogs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $authLogs->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection
