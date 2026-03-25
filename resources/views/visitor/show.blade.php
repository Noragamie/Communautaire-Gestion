@extends('layouts.guest')
@section('title', $profile->user->name . ' - CommunePro')

@section('content')
<section class="py-12 bg-white min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
            <!-- Cover Image -->
            <div class="h-48 bg-gradient-to-r from-primary-500 to-accent-500 relative">
                <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,.1) 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>

            <!-- Profile Header -->
            <div class="relative px-6 pb-6">
                <div class="flex flex-col md:flex-row md:items-end gap-6 -mt-16">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($profile->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" 
                                 alt="{{ $profile->user->name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-4xl font-bold text-white">
                                    {{ substr($profile->user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-3">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $profile->user->name }}</h1>
                                <p class="text-gray-600">{{ $profile->secteur_activite }}</p>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                            @if($profile->category)
                                <span class="px-3 py-1 bg-primary-50 text-primary-700 text-sm font-medium rounded-full">
                                    {{ $profile->category->name }}
                                </span>
                            @endif
                            @if($profile->status === 'approved')
                                <span class="px-3 py-1 bg-green-50 text-green-700 text-sm font-medium rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Vérifié
                                </span>
                            @endif
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $profile->localisation }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Title -->
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informations professionnelles
                </h2>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Biographie -->
                        @if($profile->bio)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                Biographie
                            </h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $profile->bio }}
                            </p>
                        </div>
                        @endif

                        <!-- Compétences -->
                        @if($profile->competences)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                Compétences
                            </h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $profile->competences }}
                            </p>
                        </div>
                        @endif

                        <!-- Expérience -->
                        @if($profile->experience)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                Expérience professionnelle
                            </h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $profile->experience }}
                            </p>
                        </div>
                        @endif

                        <!-- Niveau d'étude -->
                        @if($profile->niveau_etude)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                    </svg>
                                </div>
                                Niveau d'étude
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ $profile->niveau_etude }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Contact Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                Contact
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="font-medium text-gray-900 break-all">{{ $profile->user->email }}</p>
                                    </div>
                                </div>

                                @if($profile->telephone)
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500">Téléphone</p>
                                        <p class="font-medium text-gray-900">{{ $profile->telephone }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($profile->site_web)
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-500">Site web</p>
                                        <a href="{{ $profile->site_web }}" target="_blank" class="font-medium text-primary-600 hover:text-primary-700 break-all">
                                            {{ $profile->site_web }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                        <!-- Actions -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                </div>
                                Navigation
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('annuaire') }}" 
                                   class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all border border-gray-200">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    <span class="font-medium text-sm text-gray-700">Retour à l'annuaire</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
