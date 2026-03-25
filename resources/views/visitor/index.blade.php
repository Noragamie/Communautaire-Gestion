@extends('layouts.guest')
@section('title', 'Accueil - CommunePro')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-50 via-white to-accent-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in-up">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Rejoignez la communauté des
                    <span class="bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                        acteurs économiques
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    CommunePro connecte les entrepreneurs, artisans et commerçants de votre commune. 
                    Développez votre réseau, trouvez des opportunités et faites grandir votre activité.
                </p>
                <div class="flex flex-wrap gap-4">
                    @auth
                        @if(auth()->user()->isOperateur() && auth()->user()->profile)
                            <a href="{{ route('operator.profile.show') }}" class="px-8 py-4 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all hover:shadow-lg hover:scale-105">
                                Mon profil
                            </a>
                        @elseif(auth()->user()->isOperateur())
                            <a href="{{ route('operator.profile.create') }}" class="px-8 py-4 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all hover:shadow-lg hover:scale-105">
                                Créer mon profil
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all hover:shadow-lg hover:scale-105">
                            Créer mon profil
                        </a>
                    @endauth
                    <a href="{{ route('annuaire') }}" class="px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-primary-600 hover:text-primary-600 transition-all">
                        Explorer l'annuaire
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12">
                    <div>
                        <p class="text-3xl font-bold text-primary-600">{{ $profiles->total() }}+</p>
                        <p class="text-sm text-gray-600">Membres actifs</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-primary-600">{{ $categories->count() }}</p>
                        <p class="text-sm text-gray-600">Catégories</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-primary-600">{{ \App\Models\Actuality::count() }}</p>
                        <p class="text-sm text-gray-600">Événements</p>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-400 to-accent-400 rounded-3xl transform rotate-6"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-accent-500"></div>
                                <div class="flex-1">
                                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-2 bg-gray-100 rounded w-1/2"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-blue-500"></div>
                                <div class="flex-1">
                                    <div class="h-3 bg-gray-200 rounded w-2/3 mb-2"></div>
                                    <div class="h-2 bg-gray-100 rounded w-1/3"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-500 to-red-500"></div>
                                <div class="flex-1">
                                    <div class="h-3 bg-gray-200 rounded w-4/5 mb-2"></div>
                                    <div class="h-2 bg-gray-100 rounded w-2/5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Pourquoi rejoindre CommunePro ?</h2>
            <p class="text-xl text-gray-600">Des outils simples pour développer votre activité</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all hover:-translate-y-2">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Réseau professionnel</h3>
                <p class="text-gray-600">Connectez-vous avec d'autres entrepreneurs et développez votre réseau local.</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all hover:-translate-y-2">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Visibilité accrue</h3>
                <p class="text-gray-600">Augmentez votre visibilité auprès des clients et partenaires potentiels.</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-xl transition-all hover:-translate-y-2">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Opportunités</h3>
                <p class="text-gray-600">Accédez à des opportunités d'affaires et de collaboration.</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Trouvez des professionnels</h2>
            <p class="text-xl text-gray-600">Recherchez par nom, secteur ou catégorie</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <form method="GET" action="{{ route('annuaire') }}" class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nom, secteur, localisation..."
                           class="input-modern">
                    
                    <select name="category" class="input-modern">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Profiles Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Membres récents</h2>
                <p class="text-gray-600">Découvrez les nouveaux membres de la communauté</p>
            </div>
            <a href="{{ route('annuaire') }}" class="text-primary-600 hover:text-primary-700 font-semibold flex items-center gap-2">
                Voir tout
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($profiles as $profile)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="text-center">
                        @if($profile->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" 
                                 alt="{{ $profile->user->name }}" 
                                 class="w-20 h-20 rounded-xl mx-auto object-cover mb-4 border-2 border-gray-100">
                        @else
                            <div class="w-20 h-20 rounded-xl mx-auto bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center mb-4">
                                <span class="text-2xl font-bold text-white">
                                    {{ substr($profile->user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $profile->user->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $profile->secteur_activite }}</p>
                        
                        <div class="flex items-center justify-center gap-1 text-xs text-gray-500 mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $profile->localisation }}</span>
                        </div>
                        
                        @if($profile->category)
                            <span class="inline-block px-3 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-full mb-4">
                                {{ $profile->category->name }}
                            </span>
                        @endif
                        
                        <a href="{{ route('profiles.show', $profile) }}" 
                           class="block w-full px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors text-sm">
                            Voir le profil
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500">Aucun profil disponible pour le moment</p>
                </div>
            @endforelse
        </div>

        @if($profiles->hasPages())
        <div class="mt-12">
            {{ $profiles->links() }}
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-primary-600 to-accent-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @auth
            @if(auth()->user()->isOperateur() && auth()->user()->profile)
                <h2 class="text-4xl font-bold text-white mb-6">Développez votre réseau</h2>
                <p class="text-xl text-white/90 mb-8">
                    Explorez l'annuaire et connectez-vous avec d'autres professionnels de votre commune.
                </p>
                <a href="{{ route('annuaire') }}" class="inline-block px-8 py-4 bg-white text-primary-600 rounded-xl font-semibold hover:shadow-2xl transition-all hover:scale-105">
                    Explorer l'annuaire
                </a>
            @elseif(auth()->user()->isOperateur())
                <h2 class="text-4xl font-bold text-white mb-6">Complétez votre profil</h2>
                <p class="text-xl text-white/90 mb-8">
                    Créez votre profil professionnel pour rejoindre la communauté.
                </p>
                <a href="{{ route('operator.profile.create') }}" class="inline-block px-8 py-4 bg-white text-primary-600 rounded-xl font-semibold hover:shadow-2xl transition-all hover:scale-105">
                    Créer mon profil
                </a>
            @endif
        @else
            <h2 class="text-4xl font-bold text-white mb-6">Prêt à rejoindre la communauté ?</h2>
            <p class="text-xl text-white/90 mb-8">
                Créez votre profil en quelques minutes et commencez à développer votre réseau professionnel.
            </p>
            <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-primary-600 rounded-xl font-semibold hover:shadow-2xl transition-all hover:scale-105">
                Créer mon compte gratuitement
            </a>
        @endauth
    </div>
</section>
@endsection
