@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="mb-8">
    <a href="{{ route('annuaire') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4 font-medium text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour à l'annuaire
    </a>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $category->name }}</h1>
        <p class="text-sm text-gray-600">{{ $category->description }}</p>
    </div>
</div>

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
                <h3 class="font-bold text-base text-gray-900 mb-1">{{ $profile->user->name }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $profile->localisation }}</p>
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
            <p class="text-sm text-gray-500">Aucun profil dans cette catégorie</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $profiles->links() }}
</div>
@endsection
