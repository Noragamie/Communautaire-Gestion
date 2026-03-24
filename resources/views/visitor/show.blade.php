@extends('layouts.app')
@section('title', $profile->user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 font-medium text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>

    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8">
            <div class="flex items-center gap-6">
                @if($profile->photo)
                    <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}" 
                         class="w-20 h-20 rounded-full border-4 border-white shadow-lg object-cover">
                @else
                    <div class="w-20 h-20 rounded-full border-4 border-white shadow-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                @endif
                <div class="text-white">
                    <h1 class="text-2xl font-bold mb-1">{{ $profile->user->name }}</h1>
                    <p class="text-sm text-indigo-100">{{ $profile->secteur_activite }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                @if($profile->telephone)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Téléphone</p>
                            <p class="font-semibold text-sm text-gray-900">{{ $profile->telephone }}</p>
                        </div>
                    </div>
                @endif
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="font-semibold text-sm text-gray-900">{{ $profile->user->email }}</p>
                    </div>
                </div>
            </div>

            @if($profile->bio)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">À propos</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $profile->bio }}</p>
                </div>
            @endif

            @if($profile->documents->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-sm text-gray-900 mb-3">Documents</h3>
                    <div class="space-y-2">
                        @foreach($profile->documents as $doc)
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                               class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs text-gray-700">{{ $doc->file_name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
