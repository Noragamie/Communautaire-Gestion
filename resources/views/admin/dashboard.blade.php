@extends('layouts.app')

@section('title', 'Tableau de Bord Admin - Communautaire')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Tableau de Bord Admin</h1>
            <p class="text-gray-600 mt-2">Gérez votre communauté et suivez les statistiques clés</p>
        </div>
        <div class="flex gap-3">
            <button class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-all">
                Exporter
            </button>
            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold hover:shadow-medium transition-all">
                Ajouter
            </button>
        </div>
    </div>

    <!-- Key Stats -->
    <div class="grid grid-cols-4 gap-6">
        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] ?? 1284 }}</p>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 12% ce mois</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Profils Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['approved'] ?? 856 }}</p>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 8% ce mois</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">En Attente</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending'] ?? 24 }}</p>
                    <p class="text-yellow-600 text-xs font-medium mt-2">À traiter</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Rejetés</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['rejected'] ?? 12 }}</p>
                    <p class="text-red-600 text-xs font-medium mt-2">À réviser</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="col-span-2 space-y-8">
            <!-- Pending Profiles -->
            <div class="card-glass rounded-2xl p-8 shadow-soft">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Profils en Attente</h2>
                    <a href="{{ route('admin.profiles.index') }}" class="text-blue-600 text-sm font-bold hover:text-blue-700">Voir tout →</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentProfiles ?? [] as $profile)
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200 hover:border-blue-300 transition-all">
                        <div class="flex items-center gap-4 flex-1">
                            @if($profile->photo)
                                <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-blue-200 flex items-center justify-center text-blue-600 font-bold">{{ substr($profile->user->name, 0, 1) }}</div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-900">{{ $profile->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $profile->secteur_activite }} • En attente depuis 2 jours</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.profiles.approve', $profile) }}" class="px-4 py-2 rounded-lg bg-green-500 text-white text-xs font-bold hover:bg-green-600 transition-all">Approuver</a>
                            <a href="{{ route('admin.profiles.show', $profile) }}" class="px-4 py-2 rounded-lg bg-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-400 transition-all">Voir</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-600 text-center py-8">Aucun profil en attente</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card-glass rounded-2xl p-8 shadow-soft">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Activités Récentes</h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 pb-4 border-b border-blue-100">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Profil approuvé</p>
                            <p class="text-sm text-gray-600 mt-1">Marcus Thorne a été approuvé</p>
                            <p class="text-xs text-gray-500 mt-2">Il y a 2 heures</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Nouvel événement créé</p>
                            <p class="text-sm text-gray-600 mt-1">Conférence Annuelle 2024</p>
                            <p class="text-xs text-gray-500 mt-2">Il y a 4 heures</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="card-glass rounded-2xl p-6 shadow-soft">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.profiles.index') }}" class="block p-3 rounded-lg bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-all text-sm font-bold text-blue-600">
                        → Gérer les Profils
                    </a>
                    <a href="{{ route('admin.actualities.index') }}" class="block p-3 rounded-lg bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-all text-sm font-bold text-blue-600">
                        → Gérer les Événements
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block p-3 rounded-lg bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-all text-sm font-bold text-blue-600">
                        → Gérer les Utilisateurs
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="block p-3 rounded-lg bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-all text-sm font-bold text-blue-600">
                        → Gérer les Catégories
                    </a>
                </div>
            </div>

            <!-- System Health -->
            <div class="card-glass rounded-2xl p-6 shadow-soft">
                <h3 class="text-lg font-bold text-gray-900 mb-4">État du Système</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Base de données</p>
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Serveur API</p>
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Stockage</p>
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="btn-primary rounded-2xl p-6 text-white shadow-medium">
                <h3 class="text-lg font-bold mb-4">Résumé du Mois</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Nouveaux membres</span>
                        <span class="font-bold">+156</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Événements créés</span>
                        <span class="font-bold">+42</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Engagement</span>
                        <span class="font-bold">+23%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
