@extends('layouts.app')
@section('title', 'Accueil - CommunePro')

@section('content')
<!-- Hero Section : pleine largeur viewport + min 100vh sur lg + fond grille -->
<section class="relative w-screen max-w-[100vw] ml-[calc(50%-50vw)] py-16 lg:min-h-[90vh] lg:flex lg:items-center lg:py-0 overflow-visible">
    <div class="grid-wrapper" aria-hidden="true">
        <div class="grid-background"></div>
    </div>
    {{-- Pas de max-w-2xl ici : le bloc central doit pouvoir faire toute la largeur du contenu (max-w-6xl) avec cartes en dehors du flux --}}
    <div class="relative z-10 mx-auto w-full max-w-[min(100rem,calc(100vw-2rem))] px-4 sm:px-6 lg:px-8 pt-4 pb-6 lg:pt-12 lg:pb-6">
        <!-- Bloc central pleine largeur (max-w-6xl) ; cartes en position absolue à gauche / droite du bloc (hors flux) -->
        <div class="relative mx-auto w-full max-w-5xl animate-fade-in-up overflow-visible">
            {{-- text-center sans relative : les cartes xl:absolute se positionnent par rapport à max-w-5xl (sinon %/top ne voit que la hauteur du texte → top 70% ou 90% change à peine) --}}
            <div class="text-center">
            <div class="relative z-10">
            <!-- Badge -->
            <a href="{{ route('actualities') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white/80 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-white mb-8">
                <span>Restez informé des actualités de la communauté</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-900 text-white" aria-hidden="true">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
            </a>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-[1.15] tracking-tight">
                Rejoignez la communauté des
                <span class="font-playfair-italic text-gray-900 border-b-2 border-gray-200 pb-1">acteurs économiques</span>
                de votre commune
            </h1>

            <p class="text-lg sm:text-xl text-gray-500 font-normal max-w-2xl mx-auto mb-10 leading-relaxed">
                CommunePro connecte les entrepreneurs, artisans et commerçants de votre commune.
                Développez votre réseau, trouvez des opportunités et faites grandir votre activité.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    @if(auth()->user()->isOperateur() && auth()->user()->profile)
                        <a href="{{ route('operator.profile.show') }}" class="inline-flex w-full sm:w-auto justify-center px-8 py-3.5 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                            Mon profil
                        </a>
                    @elseif(auth()->user()->isOperateur())
                        <a href="{{ route('operator.profile.create') }}" class="inline-flex w-full sm:w-auto justify-center px-8 py-3.5 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                            Créer mon profil
                        </a>
                    @elseif(auth()->user()->isBackoffice())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex w-full sm:w-auto justify-center px-8 py-3.5 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                            Administration
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="inline-flex w-full sm:w-auto justify-center px-8 py-3.5 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                        Créer mon compte
                    </a>
                @endauth
                <a href="{{ route('annuaire') }}" class="inline-flex w-full sm:w-auto justify-center px-8 py-3.5 rounded-xl border-2 border-gray-200 bg-white text-gray-800 font-semibold hover:border-gray-300 transition-all">
                    Explorer l’annuaire
                </a>
            </div>
            </div>

            {{-- Même paire de cartes partout : flux (<xl) ; à partir de xl, xl:contents retire le wrapper et les cartes redeviennent absolute (N=500px). --}}
            <div class="mt-10 flex flex-wrap items-stretch justify-center gap-6 xl:mt-0 xl:contents pointer-events-none select-none" aria-hidden="true">
                <div class="shrink-0 overflow-visible xl:absolute xl:top-full xl:left-1/2 xl:z-0 xl:[transform:translateX(calc(-50%-500px))_translateY(1rem)_rotate(-6deg)]">
                    <div class="w-[13.5rem] rounded-2xl border border-gray-200/90 bg-gradient-to-br from-white to-gray-50/95 p-4 text-center shadow-xl shadow-gray-900/10 ring-1 ring-black/5 -rotate-3 sm:-rotate-6 xl:rotate-0">
                        <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold tracking-tight text-gray-900">{{ $heroProfessionnelsCount }}+</p>
                        <p class="text-xs font-medium text-gray-600">professionnels</p>
                    </div>
                </div>
                <div class="shrink-0 overflow-visible xl:absolute xl:top-full xl:left-1/2 xl:z-0 xl:[transform:translateX(calc(-50%+500px))_translateY(1rem)_rotate(3deg)]">
                    <div class="w-[13.5rem] rounded-2xl border border-gray-200/90 bg-gradient-to-br from-white to-gray-50/95 p-4 text-center shadow-xl shadow-gray-900/10 ring-1 ring-black/5 rotate-3 xl:rotate-0">
                        <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-accent-50 text-accent-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold tracking-tight text-gray-900">{{ $heroSecteursCount }}+</p>
                        <p class="text-xs font-medium text-gray-600">secteurs d’activité</p>
                    </div>
                </div>
            </div>

            <!-- Social proof -->
            <!-- <div class="mt-14 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
                <div class="flex -space-x-3" aria-hidden="true">
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-gradient-to-br from-primary-400 to-primary-600"></div>
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-gradient-to-br from-accent-400 to-accent-600"></div>
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-gradient-to-br from-emerald-400 to-teal-600"></div>
                    <div class="h-10 w-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">+</div>
                </div>
                <p class="text-sm text-gray-500 max-w-xs sm:max-w-none sm:text-left">
                    <span class="font-semibold text-gray-800">{{ $profiles->total() }}+ membres</span> déjà présents sur la plateforme
                </p>
            </div> -->
            </div>
        </div>
    </div>
</section>

<!-- Pont scroll : ligne en tirets + flèche vers la suite -->
<div class="relative w-screen max-w-[100vw] ml-[calc(50%-50vw)] flex justify-center py-2 lg:py-3">
    <a href="#accueil-suite"
       class="animate-scroll-hint flex flex-col items-center rounded-full p-2 text-gray-400 transition-colors hover:text-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
       aria-label="Aller à la suite de la page">
        <svg class="h-[4.25rem] w-8" viewBox="0 0 32 76" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M16 4v50" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="5 7"/>
            <path d="M9 58l7 7 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

<!-- Features Section -->
<section id="accueil-suite" class="scroll-mt-24 py-20 bg-white">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
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
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Trouvez des professionnels</h2>
            <p class="text-xl text-gray-600">Recherchez par nom, secteur ou catégorie</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <form method="GET" action="{{ route('annuaire') }}" class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nom, secteur, commune…"
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
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <img src="{{ image_url($profile, 'photo', 'photo_data') }}" 
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
                            <span>{{ $profile->user->commune?->name ?? $profile->localisation }}</span>
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
