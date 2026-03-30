@extends('layouts.app')
@section('title', 'Mon profil - CommunePro')

@php
    $niveauLabels = [
        'bac' => 'Baccalauréat',
        'licence' => 'Licence',
        'master' => 'Master',
        'doctorat' => 'Doctorat',
        'autre' => 'Autre',
    ];
    $statusLabels = [
        'pending' => 'En attente de validation',
        'approved' => 'Profil validé',
        'rejected' => 'Profil refusé',
        'suspended' => 'Profil suspendu',
    ];
    $rawComp = $profile->competences ?? '';
    $competenceTags = collect(preg_split('/\r\n|\r|\n|,|;|\|/', $rawComp, -1, PREG_SPLIT_NO_EMPTY))
        ->map(fn ($s) => trim($s))
        ->filter()
        ->values();
    $docTypeLabels = ['cv' => 'CV', 'other' => 'Autre document'];
    $pendingModification = $profile->modificationRequest && $profile->modificationRequest->isPending();
@endphp

@section('content')
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 rounded-2xl border border-gray-100">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Mon profil</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">
                    Retrouvez ici l’ensemble de vos informations, le statut de votre fiche et ce qui est visible dans l’annuaire public.
                </p>
            </div>
        </div>

        {{-- Hero fiche --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                <div class="flex items-start gap-5 flex-1 min-w-0">
                    @if($profile->photo)
                        <div class="flex-shrink-0 w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border border-gray-100 shadow-sm overflow-hidden bg-gray-50">
                            <img src="{{ asset('storage/'.$profile->photo) }}"
                                 alt=""
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="flex-shrink-0 w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-sm">
                            <span class="text-3xl sm:text-4xl font-bold text-white">{{ mb_substr($profile->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-1">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ $profile->user->name }}</h1>
                            @if($profile->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-800 border border-green-200">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Vérifié
                                </span>
                            @endif
                        </div>
                        <p class="text-base sm:text-lg text-gray-600 font-medium">
                            {{ $profile->secteur_activite ?: 'Secteur non renseigné' }}
                        </p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                            @php($lieu = $profile->user->commune?->name ?? $profile->localisation)
                            @if($lieu)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $lieu }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Inscrit le {{ $profile->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row lg:flex-col gap-2 lg:items-end lg:flex-shrink-0">
                    <a href="{{ route('operator.profile.edit') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 shadow-sm transition-colors">
                        Modifier mon profil
                    </a>
                    @if($profile->isApproved())
                        <a href="{{ route('profiles.show', $profile) }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 hover:border-gray-300 transition-colors">
                            Voir le profil public
                        </a>
                    @else
                        <button type="button" disabled
                                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-400 text-sm font-semibold cursor-not-allowed"
                                title="Votre profil n'est pas encore validé">
                            Voir le profil public
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if($pendingModification)
            <div class="mb-6 bg-primary-50 border border-primary-100 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold text-primary-900">Demande de modification en cours</p>
                    <p class="text-sm text-primary-800 mt-0.5">Vos derniers changements sont à l’étude. Votre fiche publique reste inchangée jusqu’à la décision de l’administration.</p>
                </div>
            </div>
        @endif

        @if($profile->status === 'pending')
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold text-yellow-900">Profil en attente de validation</p>
                    <p class="text-sm text-yellow-800">Votre profil est en cours de vérification par notre équipe. Vous ne pouvez pas encore voir votre profil public.</p>
                </div>
            </div>
        @elseif($profile->status === 'rejected')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold text-red-900">Profil refusé</p>
                    @if($profile->motif_rejet)
                        <p class="text-sm text-red-800 mt-1"><span class="font-medium">Motif :</span> {{ $profile->motif_rejet }}</p>
                    @endif
                    <p class="text-sm text-red-800 mt-2">Modifiez votre profil puis soumettez-le à nouveau pour validation.</p>
                </div>
            </div>
        @elseif($profile->status === 'suspended')
            <div class="mb-6 bg-gray-100 border border-gray-300 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-gray-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                <div>
                    <p class="font-semibold text-gray-900">Profil suspendu</p>
                    <p class="text-sm text-gray-700 mt-1">Votre fiche n’est plus visible publiquement. Contactez l’administration pour plus d’informations.</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">À propos</h2>
                    @if($profile->bio)
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $profile->bio }}</div>
                    @else
                        <p class="text-gray-500">Aucune présentation renseignée.</p>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Compétences</h2>
                    @if($competenceTags->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach($competenceTags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200/80">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Aucune compétence renseignée.</p>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Expérience</h2>
                    @if($profile->experience)
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $profile->experience }}</div>
                    @else
                        <p class="text-gray-500">Aucune expérience renseignée.</p>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Formation &amp; catégorie</h2>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Niveau d’études</dt>
                            <dd class="text-gray-900 font-medium">
                                {{ $profile->niveau_etude ? ($niveauLabels[$profile->niveau_etude] ?? $profile->niveau_etude) : 'Non renseigné' }}
                            </dd>
                        </div>
                        @if($profile->category)
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Catégorie</dt>
                                <dd>
                                    <span class="inline-flex px-3 py-1 rounded-lg text-sm font-medium bg-primary-50 text-primary-800 border border-primary-100">{{ $profile->category->name }}</span>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Statut du profil</p>
                    <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $statusLabels[$profile->status] ?? $profile->status }}</p>
                    @if($profile->status === 'approved')
                        <p class="text-sm text-gray-500 mt-2">Visible dans l’annuaire public selon les règles de la plateforme.</p>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Contact
                    </h2>

                    <form method="POST" action="{{ route('operator.profile.contact-visible') }}" class="mb-6">
                        @csrf
                        @method('PATCH')
                        <div class="rounded-xl border border-gray-200 bg-gray-50/90 p-4 sm:p-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Coordonnées sur la fiche publique</p>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1.5 leading-relaxed max-w-md">
                                        Contrôlez si l’email, le téléphone et le site web sont affichés aux visiteurs sur votre page profil dans l’annuaire.
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0 sm:pl-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold border
                                        {{ $profile->contact_visible
                                            ? 'bg-green-50 text-green-800 border-green-200'
                                            : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                        {{ $profile->contact_visible ? 'Visibles' : 'Masquées' }}
                                    </span>
                                    <button type="submit"
                                            class="relative inline-flex h-7 w-12 flex-shrink-0 rounded-full transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2
                                                {{ $profile->contact_visible ? 'bg-primary-600' : 'bg-gray-300' }}"
                                            role="switch"
                                            aria-checked="{{ $profile->contact_visible ? 'true' : 'false' }}"
                                            aria-label="{{ $profile->contact_visible ? 'Masquer les coordonnées sur la fiche publique' : 'Afficher les coordonnées sur la fiche publique' }}">
                                        <span class="sr-only">Basculer la visibilité des coordonnées</span>
                                        <span class="pointer-events-none absolute top-1 left-1 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200 ease-out
                                            {{ $profile->contact_visible ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 font-semibold uppercase">Email</p>
                                <p class="text-gray-900 text-sm break-all">{{ $profile->user->email }}</p>
                            </div>
                        </div>
                        @if($profile->telephone)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Téléphone</p>
                                    <p class="text-gray-900 text-sm">{{ $profile->telephone }}</p>
                                </div>
                            </div>
                        @endif
                        @if($profile->site_web)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Site web</p>
                                    <a href="{{ $profile->site_web }}" target="_blank" rel="noopener noreferrer" class="text-sm text-primary-600 hover:text-primary-700 font-medium break-all">{{ $profile->site_web }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Documents</h2>
                    @if($profile->documents->isEmpty())
                        <p class="text-sm text-gray-500">Aucun document joint.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($profile->documents as $doc)
                                <li class="flex items-center justify-between gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $doc->original_name ?? basename($doc->path) }}</p>
                                        <p class="text-xs text-gray-500">{{ $docTypeLabels[$doc->type] ?? ucfirst($doc->type) }}@if($doc->size) · {{ number_format($doc->size / 1024, 1) }} Ko @endif</p>
                                    </div>
                                    <a href="{{ asset('storage/'.$doc->path) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="flex-shrink-0 text-sm font-semibold text-primary-600 hover:text-primary-700">
                                        Ouvrir
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Raccourcis</h2>
                    <div class="space-y-1">
                        <a href="{{ route('operator.profile.edit') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier le profil
                        </a>
                        <a href="{{ route('operator.announcements.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            Mes annonces
                        </a>
                        <a href="{{ route('operator.settings') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Paramètres du compte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
