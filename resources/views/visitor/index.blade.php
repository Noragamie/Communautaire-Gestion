@extends('layouts.app')
@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="grid md:grid-cols-2 gap-0 items-center">
        <div class="p-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Annuaire Communautaire
            </h1>
            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                Découvrez et connectez-vous avec les acteurs locaux de votre communauté. 
                Explorez les profils par catégorie et trouvez les services dont vous avez besoin.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('annuaire') }}" 
                   class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 font-medium transition-all text-sm">
                    Explorer l'annuaire
                </a>
                @guest
                <a href="{{ route('register') }}" 
                   class="border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-50 font-medium transition-all text-sm">
                    Créer un compte
                </a>
                @endguest
            </div>
        </div>
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-12 flex items-center justify-center min-h-[300px]">
            <svg class="w-48 h-48 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Rechercher par nom, secteur..."
               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
        <select name="category" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium transition-all text-sm">
            Rechercher
        </button>
    </form>
</div>

<!-- Profiles Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($profiles as $profile)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="text-center">
                @if($profile->photo)
                    <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}" 
                         class="w-20 h-20 rounded-full mx-auto mb-3 object-cover border-2 border-gray-100">
                @else
                    <div class="w-20 h-20 rounded-full mx-auto mb-3 bg-gray-100 flex items-center justify-center border-2 border-gray-100">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                @endif
                <h3 class="font-semibold text-base text-gray-900 mb-1">{{ $profile->user->name }}</h3>
                <p class="text-sm text-gray-500 mb-1">{{ $profile->secteur_activite }}</p>
                <p class="text-xs text-gray-400 mb-4">{{ $profile->localisation }}</p>
                <a href="{{ route('profiles.show', $profile) }}" 
                   class="inline-block w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium transition-all text-sm">
                    Voir le profil
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-sm text-gray-500">Aucun profil trouvé</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $profiles->links() }}
</div>
@endsection
