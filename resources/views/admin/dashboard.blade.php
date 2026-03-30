@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="page-content">
    <!-- Header -->
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tableau de bord</h1>
        <p class="text-gray-600">Vue d'ensemble de votre plateforme communautaire</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                @if(($stats['total_delta'] ?? null) !== null)
                    @if($stats['total_delta'] > 0)
                        <span class="text-sm font-semibold text-green-600" title="Nouveaux profils ce mois vs mois dernier">+{{ $stats['total_delta'] }}</span>
                    @elseif($stats['total_delta'] < 0)
                        <span class="text-sm font-semibold text-red-600" title="Nouveaux profils ce mois vs mois dernier">{{ $stats['total_delta'] }}</span>
                    @else
                        <span class="text-sm font-medium text-gray-400">=</span>
                    @endif
                @endif
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total profils</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            @if(($stats['total_delta'] ?? null) !== null)
                <p class="text-xs text-gray-500 mt-2">Variation mois en cours vs mois précédent (créations)</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Approuvés</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Rejetés</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
        </div>
    </div>

    <script type="application/json" id="dashboard-charts-payload">@json($chartPayload)</script>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Répartition des statuts</h2>
            <p class="text-sm text-gray-600 mb-4">Profils par état sur le périmètre actuel</p>
            <div class="h-72 relative">
                <canvas id="chart-status" aria-label="Graphique statuts"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Profils par catégorie</h2>
            <p class="text-sm text-gray-600 mb-4">Répartition des opérateurs par catégorie</p>
            <div class="h-72 relative">
                <canvas id="chart-categories" aria-label="Graphique catégories"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Nouveaux profils (6 mois)</h2>
            <p class="text-sm text-gray-600 mb-4">Nombre de profils créés par mois</p>
            <div class="h-72 relative">
                <canvas id="chart-activity" aria-label="Graphique activité profils"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Annonces et actualités</h2>
            <p class="text-sm text-gray-600 mb-4">
                Contenus créés par mois
                @if(auth()->user()->isAdmin() && \App\Services\CommuneContext::isAdminViewingAllManagedCommunes())
                    (toutes vos communes)
                @else
                    (commune active)
                @endif
            </p>
            <div class="h-72 relative">
                <canvas id="chart-content" aria-label="Graphique annonces et actualités"></canvas>
            </div>
        </div>
    </div>

    @if(!empty($showCommuneComparison) && !empty($chartPayload['communes']['labels']))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Profils approuvés par commune</h2>
            <p class="text-sm text-gray-600 mb-4">Vue globale sur les communes que vous gérez (admin)</p>
            <div class="h-72 max-w-3xl relative">
                <canvas id="chart-communes" aria-label="Graphique communes"></canvas>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.profiles.index') }}" class="group bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-6 hover:shadow-2xl hover:shadow-primary-500/30 transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Gérer les profils</h3>
            <p class="text-white/80 text-sm">Approuver, modifier ou supprimer des profils</p>
        </a>

        <a href="{{ route('admin.actualities.index') }}" class="group bg-gradient-to-br from-accent-500 to-accent-600 rounded-2xl p-6 hover:shadow-2xl hover:shadow-accent-500/30 transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Événements</h3>
            <p class="text-white/80 text-sm">Créer et gérer les actualités</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="group bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 hover:shadow-2xl hover:shadow-green-500/30 transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Utilisateurs</h3>
            <p class="text-white/80 text-sm">Gérer les comptes utilisateurs</p>
        </a>
    </div>

    <!-- Recent Profiles -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Profils récents</h2>
                <p class="text-sm text-gray-600 mt-1">Dernières soumissions de profils</p>
            </div>
            <a href="{{ route('admin.profiles.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm flex items-center gap-1">
                <span>Voir tout</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
        
        <div class="space-y-3">
            @forelse($recentProfiles as $profile)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all group">
                    <div class="flex items-center gap-4">
                        @if($profile->photo)
                            <img src="{{ image_url($profile, 'photo', 'photo_data') }}" alt="{{ $profile->user->name }}"
                                 class="w-12 h-12 rounded-xl object-cover ring-2 ring-gray-200 group-hover:ring-primary-500 transition-all">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-primary-500 transition-all">
                                <span class="text-lg font-bold text-white">
                                    {{ substr($profile->user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                {{ $profile->user->name }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $profile->secteur_activite }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($profile->status === 'approved')
                            <x-badge type="success">Approuvé</x-badge>
                        @elseif($profile->status === 'pending')
                            <x-badge type="warning">En attente</x-badge>
                        @elseif($profile->status === 'suspended')
                            <x-badge type="default">Suspendu</x-badge>
                        @else
                            <x-badge type="danger">Rejeté</x-badge>
                        @endif
                        
                        <a href="{{ route('admin.profiles.show', $profile) }}"
                           class="px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-all">
                            Voir
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-gray-600">Aucun profil récent</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
