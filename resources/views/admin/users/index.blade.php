@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des utilisateurs</h1>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.users.agents.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvel agent municipal
            </a>
        @endif
    </div>

    @if(!empty($showCommuneFilter))
        <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
            <div>
                <label for="filter_commune_users" class="block text-xs font-medium text-gray-500 mb-1">Filtrer par commune</label>
                <select id="filter_commune_users" name="commune_id" onchange="this.form.submit()"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-primary-500 min-w-[12rem]">
                    <option value="">Toutes (périmètre)</option>
                    @foreach($managedCommunesForFilter as $c)
                        <option value="{{ $c->id }}" @selected((string) request('commune_id') === (string) $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Commune</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Rôle</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $user->commune?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleClass = match ($user->role) {
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'agent_municipal' => 'bg-indigo-100 text-indigo-800',
                                    default => 'bg-primary-100 text-primary-800',
                                };
                                $roleLabel = match ($user->role) {
                                    'agent_municipal' => 'Agent municipal',
                                    default => ucfirst($user->role),
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $roleClass }}">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_suspended)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    Suspendu
                                </span>
                            @elseif($user->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Actif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3 flex-wrap">
                                @if(!$user->isAdmin() && !($user->isAgentMunicipal() && auth()->user()->isAgentMunicipal() && $user->id !== auth()->id()))
                                    {{-- Activer / Désactiver --}}
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-sm font-medium transition-colors
                                                    {{ $user->is_active ? 'text-gray-500 hover:text-gray-700' : 'text-primary-600 hover:text-primary-700' }}">
                                            {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>

                                    @if(!($user->isAgentMunicipal() && auth()->user()->isAgentMunicipal() && $user->id !== auth()->id()))
                                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-sm font-medium transition-colors
                                                    {{ $user->is_suspended ? 'text-green-600 hover:text-green-700' : 'text-orange-600 hover:text-orange-700' }}">
                                            {{ $user->is_suspended ? 'Lever la suspension' : 'Suspendre' }}
                                        </button>
                                    </form>
                                    @endif

                                    @if(!($user->isAgentMunicipal() && auth()->user()->isAgentMunicipal()))
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                    @endif
                                @endif

                                @if(auth()->user()->isAdmin() || ! $user->isAgentMunicipal())
                                <a href="{{ route('admin.users.logs', $user) }}"
                                   class="text-gray-500 hover:text-gray-800 text-sm font-medium transition-colors">
                                    Logs
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
