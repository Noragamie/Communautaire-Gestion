@extends('layouts.admin')

@section('title', 'Gestion des profils')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des profils</h1>
            <p class="text-gray-600 mt-1">Examinez et approuvez les profils des opérateurs</p>
        </div>
        <a href="{{ route('admin.export.excel') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exporter en Excel
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher par nom ou email..."
                   class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 placeholder-gray-500">
            <select name="status" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-xl font-semibold transition-all">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-2">Total des profils</p>
            <p class="text-3xl font-bold text-gray-900">{{ $profiles->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-yellow-600 mb-2">En attente</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $profiles->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-green-600 mb-2">Approuvés</p>
            <p class="text-3xl font-bold text-green-600">{{ $profiles->where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-red-600 mb-2">Rejetés</p>
            <p class="text-3xl font-bold text-red-600">{{ $profiles->where('status', 'rejected')->count() }}</p>
        </div>
    </div>

    <!-- Profiles Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Catégorie</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Localisation</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Soumis le</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($profiles as $profile)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($profile->photo)
                                        <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}"
                                             class="w-10 h-10 rounded-xl object-cover ring-2 ring-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center ring-2 ring-gray-200">
                                            <span class="text-sm font-bold text-white">{{ substr($profile->user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $profile->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $profile->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $profile->category->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $profile->localisation }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $profile->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $profile->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $profile->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $profile->status === 'pending' ? 'En attente' : ($profile->status === 'approved' ? 'Approuvé' : 'Rejeté') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $profile->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open"
                                            class="p-2 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">

                                        {{-- Voir --}}
                                        <a href="{{ route('admin.profiles.show', $profile) }}"
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Voir
                                        </a>

                                        <div class="border-t border-gray-100 my-1"></div>

                                        {{-- Désactiver / Activer le compte --}}
                                        <form method="POST" action="{{ route('admin.users.toggle', $profile->user) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors
                                                           {{ $profile->user->is_active ? 'text-gray-700 hover:bg-gray-50' : 'text-primary-600 hover:bg-primary-50' }}">
                                                <svg class="w-4 h-4 {{ $profile->user->is_active ? 'text-gray-400' : 'text-primary-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                {{ $profile->user->is_active ? 'Désactiver' : 'Activer le compte' }}
                                            </button>
                                        </form>

                                        {{-- Suspendre / Lever la suspension --}}
                                        <form method="POST" action="{{ route('admin.users.suspend', $profile->user) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors
                                                           {{ $profile->user->is_suspended ? 'text-green-600 hover:bg-green-50' : 'text-orange-600 hover:bg-orange-50' }}">
                                                <svg class="w-4 h-4 {{ $profile->user->is_suspended ? 'text-green-400' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                {{ $profile->user->is_suspended ? 'Lever suspension' : 'Suspendre' }}
                                            </button>
                                        </form>

                                        <div class="border-t border-gray-100 my-1"></div>

                                        {{-- Supprimer le profil --}}
                                        <form method="POST" action="{{ route('admin.profiles.destroy', $profile) }}"
                                              onsubmit="return confirm('Supprimer définitivement ce profil ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors">
                                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600 font-medium">Aucun profil trouvé</p>
                                <p class="text-gray-500 text-sm">Essayez de modifier vos filtres de recherche</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $profiles->links() }}
    </div>
@endsection
