@extends('layouts.app')
@section('title', $profile->user->name . ' - CommunePro')

@php
    $niveauLabels = [
        'bac' => 'Baccalauréat',
        'licence' => 'Licence',
        'master' => 'Master',
        'doctorat' => 'Doctorat',
        'autre' => 'Autre',
    ];
    $rawComp = $profile->competences ?? '';
    $competenceTags = collect(preg_split('/\r\n|\r|\n|,|;|\|/', $rawComp, -1, PREG_SPLIT_NO_EMPTY))
        ->map(fn ($s) => trim($s))
        ->filter()
        ->values();
@endphp

@section('content')
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 sm:py-10 rounded-2xl border border-gray-100 min-h-[60vh]">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('annuaire') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour à l’annuaire
            </a>
        </div>

        {{-- Hero --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                @if($profile->photo)
                    <div class="flex-shrink-0 w-28 h-28 sm:w-32 sm:h-32 rounded-2xl border border-gray-100 shadow-sm overflow-hidden bg-gray-50 mx-auto lg:mx-0">
                        <img src="{{ asset('storage/'.$profile->photo) }}" alt="" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="flex-shrink-0 w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-sm mx-auto lg:mx-0">
                        <span class="text-3xl sm:text-4xl font-bold text-white">{{ mb_substr($profile->user->name, 0, 1) }}</span>
                    </div>
                @endif

                <div class="min-w-0 flex-1 text-center lg:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ $profile->user->name }}</h1>
                    <p class="text-base sm:text-lg text-gray-600 font-medium mt-1">{{ $profile->secteur_activite ?: '—' }}</p>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mt-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-800 border border-green-200">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Profil vérifié
                        </span>
                        @if($profile->category)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-100">{{ $profile->category->name }}</span>
                        @endif
                        @if($profile->localisation)
                            <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $profile->localisation }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Fiche mise à jour le {{ $profile->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-6">
                @if($profile->bio)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">À propos</h2>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $profile->bio }}</div>
                    </div>
                @endif

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Compétences</h2>
                    @if($competenceTags->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach($competenceTags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200/80">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Non renseigné.</p>
                    @endif
                </div>

                @if($profile->experience)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Expérience</h2>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $profile->experience }}</div>
                    </div>
                @endif

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Formation</h2>
                    <p class="text-gray-900 font-medium">
                        {{ $profile->niveau_etude ? ($niveauLabels[$profile->niveau_etude] ?? $profile->niveau_etude) : 'Non renseigné' }}
                    </p>
                </div>
            </div>

            {{-- Sidebar contact --}}
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Contact
                    </h2>

                    @if($profile->contact_visible)
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Email</p>
                                    <a href="mailto:{{ $profile->user->email }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium break-all">{{ $profile->user->email }}</a>
                                </div>
                            </div>
                            @if($profile->telephone)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Téléphone</p>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $profile->telephone) }}" class="text-sm text-gray-900 font-medium">{{ $profile->telephone }}</a>
                                    </div>
                                </div>
                            @endif
                            @if($profile->site_web)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-500 uppercase">Site web</p>
                                        <a href="{{ $profile->site_web }}" target="_blank" rel="noopener noreferrer" class="text-sm text-primary-600 hover:text-primary-700 font-medium break-all">{{ $profile->site_web }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex items-start gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Coordonnées non affichées</p>
                                <p class="text-sm text-gray-600 mt-1">Ce membre a choisi de ne pas rendre ses coordonnées publiques sur la plateforme.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <a href="{{ route('annuaire') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Retour à l’annuaire
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 mt-2 rounded-xl text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                        Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
