@extends('layouts.admin')

@section('title', 'Gestion des profils')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestion des profils</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Examinez et approuvez les profils des opérateurs</p>
        </div>
        <a href="{{ route('admin.export.excel') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exporter en Excel
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher par nom ou email..."
                   class="flex-1 px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
            <select name="status" class="px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent text-gray-900 dark:text-white">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
            <button type="submit" class="bg-accent-blue hover:bg-accent-blue/90 text-white px-8 py-3 rounded-xl font-semibold transition-colors">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 p-6">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total des profils</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $profiles->total() }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 p-6">
            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400 mb-2">En attente</p>
            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $profiles->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 p-6">
            <p class="text-sm font-medium text-green-600 dark:text-green-400 mb-2">Approuvés</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $profiles->where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 p-6">
            <p class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">Rejetés</p>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $profiles->where('status', 'rejected')->count() }}</p>
        </div>
    </div>

    <!-- Profiles Table -->
    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-dark-700 border-b border-gray-200 dark:border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Utilisateur</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Catégorie</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Localisation</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Statut</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Soumis le</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse($profiles as $profile)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($profile->photo)
                                        <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-dark-600 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $profile->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $profile->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $profile->category->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $profile->localisation }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $profile->status === 'approved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : '' }}
                                    {{ $profile->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200' : '' }}
                                    {{ $profile->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' : '' }}">
                                    {{ $profile->status === 'pending' ? 'En attente' : ($profile->status === 'approved' ? 'Approuvé' : 'Rejeté') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $profile->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.profiles.show', $profile) }}"
                                   class="inline-flex items-center gap-2 text-accent-blue hover:text-accent-blue/80 font-medium text-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Détails
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">Aucun profil trouvé</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm">Essayez de modifier vos filtres de recherche</p>
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
