@extends('layouts.app')
@section('title', $category->name . ' - CommunePro')

@section('content')
<section class="py-12 bg-white">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('annuaire') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-8 font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à l'annuaire
        </a>

        <!-- Category Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $category->name }}</h1>
            <p class="text-lg text-gray-600">{{ $category->description }}</p>
            <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-700 rounded-full text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ $profiles->total() }} {{ $profiles->total() > 1 ? 'membres' : 'membre' }}
            </div>
        </div>

        <!-- Profiles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($profiles as $profile)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all hover:-translate-y-1 group">
                    <div class="p-6 text-center">
                        @if($profile->photo)
                            <img src="{{ image_url($profile, 'photo', 'photo_data') }}" alt="{{ $profile->user->name }}" 
                                 class="w-20 h-20 rounded-full mx-auto mb-4 object-cover ring-4 ring-gray-100 group-hover:ring-primary-100 transition-all">
                        @else
                            <div class="w-20 h-20 rounded-full mx-auto mb-4 bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center ring-4 ring-gray-100 group-hover:ring-primary-100 transition-all">
                                <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-primary-600 transition-colors">{{ $profile->user->name }}</h3>
                        <p class="text-sm text-gray-500 mb-1">{{ $profile->secteur_activite }}</p>
                        <p class="text-xs text-gray-400 mb-4 flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $profile->user->commune?->name ?? $profile->localisation }}
                        </p>
                        <a href="{{ route('profiles.show', $profile) }}" 
                           class="inline-block w-full bg-primary-600 text-white px-4 py-2.5 rounded-xl hover:bg-primary-700 font-semibold transition-all text-sm shadow-lg hover:shadow-xl">
                            Voir le profil
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-20 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun profil dans cette catégorie</h3>
                    <p class="text-gray-600">Revenez plus tard pour découvrir de nouveaux membres.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($profiles->hasPages())
        <div class="mt-12">
            {{ $profiles->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
