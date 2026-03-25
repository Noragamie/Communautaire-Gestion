@extends('layouts.app')
@section('title', 'Mon Profil - CommunePro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header with gradient cover -->
    <div class="bg-gradient-to-r from-primary-500 to-accent-500 rounded-2xl p-8 mb-6 shadow-lg">
        <div class="flex items-center gap-6">
            @if($profile->photo)
                <img src="{{ asset('storage/'.$profile->photo) }}" 
                     alt="{{ $profile->user->name }}" 
                     class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-lg">
            @else
                <div class="w-24 h-24 rounded-2xl bg-white flex items-center justify-center shadow-lg">
                    <span class="text-4xl font-bold text-primary-600">
                        {{ substr($profile->user->name, 0, 1) }}
                    </span>
                </div>
            @endif
            
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-white">{{ $profile->user->name }}</h1>
                    @if($profile->status === 'approved')
                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Vérifié
                        </span>
                    @endif
                </div>
                <p class="text-white/90 text-lg mb-1">{{ $profile->secteur_activite }}</p>
                <div class="flex items-center gap-2 text-white/80 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $profile->localisation }}</span>
                </div>
            </div>
            
            <a href="{{ route('operator.profile.edit') }}" 
               class="px-6 py-3 bg-white text-primary-600 rounded-xl font-semibold hover:shadow-xl transition-all hover:scale-105">
                Modifier mon profil
            </a>
        </div>
    </div>

    <!-- Status Alerts -->
    @if($profile->status === 'pending')
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold text-yellow-900">Profil en attente de validation</p>
                <p class="text-sm text-yellow-700">Votre profil est en cours de vérification par notre équipe.</p>
            </div>
        </div>
    @elseif($profile->status === 'rejected')
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold text-red-900">Profil refusé</p>
                @if($profile->rejection_reason)
                    <p class="text-sm text-red-700 mt-1">Raison : {{ $profile->rejection_reason }}</p>
                @endif
                <p class="text-sm text-red-700 mt-2">Veuillez modifier votre profil et le soumettre à nouveau.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- About Section -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    À propos
                </h2>
                <p class="text-gray-700 leading-relaxed">{{ $profile->description ?? 'Aucune description disponible.' }}</p>
            </div>

            <!-- Category -->
            @if($profile->category)
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Catégorie
                </h2>
                <span class="inline-block px-4 py-2 bg-primary-50 text-primary-700 rounded-lg font-medium">
                    {{ $profile->category->name }}
                </span>
            </div>
            @endif

            <!-- Documents -->
            @if($profile->documents->count() > 0)
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documents
                </h2>
                <div class="space-y-3">
                    @foreach($profile->documents as $document)
                        <a href="{{ asset('storage/'.$document->file_path) }}" 
                           target="_blank"
                           class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $document->type }}</p>
                                    <p class="text-sm text-gray-500">{{ basename($document->file_path) }}</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Info -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact
                </h2>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                            <p class="text-gray-900">{{ $profile->user->email }}</p>
                        </div>
                    </div>
                    
                    @if($profile->telephone)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Téléphone</p>
                            <p class="text-gray-900">{{ $profile->telephone }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($profile->site_web)
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Site web</p>
                            <a href="{{ $profile->site_web }}" target="_blank" class="text-primary-600 hover:text-primary-700 break-all">
                                {{ $profile->site_web }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Actions rapides</h2>
                <div class="space-y-2">
                    <a href="{{ route('operator.profile.edit') }}" 
                       class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="font-medium">Modifier le profil</span>
                    </a>
                    
                    <a href="{{ route('operator.announcements.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="font-medium">Mes annonces</span>
                    </a>
                    
                    <a href="{{ route('profiles.show', $profile) }}" 
                       class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-colors group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="font-medium">Voir profil public</span>
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl p-6 text-white shadow-lg">
                <h2 class="text-lg font-bold mb-4">Statistiques</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-white/80">Vues du profil</span>
                        <span class="text-2xl font-bold">{{ $profile->views ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/80">Annonces actives</span>
                        <span class="text-2xl font-bold">{{ $profile->announcements->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/80">Membre depuis</span>
                        <span class="font-semibold">{{ $profile->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
