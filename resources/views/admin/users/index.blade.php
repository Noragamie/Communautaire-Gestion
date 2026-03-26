@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestion des utilisateurs</h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
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
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-primary-100 text-primary-800' }}">
                                {{ ucfirst($user->role) }}
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
                                @if(!$user->isAdmin())
                                    {{-- Activer / Désactiver --}}
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-sm font-medium transition-colors
                                                    {{ $user->is_active ? 'text-gray-500 hover:text-gray-700' : 'text-primary-600 hover:text-primary-700' }}">
                                            {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>

                                    {{-- Suspendre / Lever la suspension --}}
                                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-sm font-medium transition-colors
                                                    {{ $user->is_suspended ? 'text-green-600 hover:text-green-700' : 'text-orange-600 hover:text-orange-700' }}">
                                            {{ $user->is_suspended ? 'Lever la suspension' : 'Suspendre' }}
                                        </button>
                                    </form>

                                    {{-- Supprimer --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.users.logs', $user) }}"
                                   class="text-gray-500 hover:text-gray-800 text-sm font-medium transition-colors">
                                    Logs
                                </a>
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
