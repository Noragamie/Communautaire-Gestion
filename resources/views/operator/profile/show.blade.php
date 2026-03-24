@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>
        @if($profile)
            <a href="{{ route('operator.profile.edit') }}" 
               class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium text-sm transition-all">
                Modifier
            </a>
        @endif
    </div>

    @if(!$profile)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-gray-600 mb-6">Vous n'avez pas encore créé de profil.</p>
            <a href="{{ route('operator.profile.create') }}" 
               class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium text-sm transition-all">
                Créer mon profil
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
                <div class="flex items-center gap-6">
                    @if($profile->photo)
                        <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}" 
                             class="w-20 h-20 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-20 h-20 rounded-full border-4 border-white shadow-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold">{{ $profile->user->name }}</h2>
                        <p class="text-sm text-indigo-100 mt-1">{{ $profile->secteur_activite }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $profile->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $profile->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $profile->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        Statut: {{ ucfirst($profile->status) }}
                    </span>
                </div>

                @if($profile->motif_rejet)
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <strong class="text-sm text-red-800 font-semibold">Motif de rejet:</strong>
                                <p class="text-sm text-red-700 mt-1">{{ $profile->motif_rejet }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Catégorie</p>
                            <p class="font-semibold text-sm text-gray-900">{{ $profile->category->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Localisation</p>
                            <p class="font-semibold text-sm text-gray-900">{{ $profile->localisation }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Secteur d'activité</p>
                            <p class="font-semibold text-sm text-gray-900">{{ $profile->secteur_activite }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Téléphone</p>
                            <p class="font-semibold text-sm text-gray-900">{{ $profile->telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>

                @if($profile->bio)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-sm text-gray-900 mb-2">À propos</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $profile->bio }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
