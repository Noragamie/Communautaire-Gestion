@extends('layouts.guest')
@section('title', $profile->user->name . ' - CommunePro')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-8 font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à l'annuaire
        </a>

        <!-- Profile Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
            <!-- Header with gradient -->
            <div class="relative h-48 bg-gradient-to-br from-primary-500 via-primary-600 to-accent-500">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yLjIxIDEuNzktNCA0LTRzNCAxLjc5IDQgNC0xLjc5IDQtNCA0LTQtMS43OS00LTR6bTAgMTBjMC0yLjIxIDEuNzktNCA0LTRzNCAxLjc5IDQgNC0xLjc5IDQtNCA0LTQtMS43OS00LTR6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-30"></div>
            </div>

            <!-- Profile Content -->
            <div class="relative px-8 pb-8">
                <!-- Avatar -->
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 -mt-20 mb-8">
                    @if($profile->photo)
                        <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}" 
                             class="w-32 h-32 rounded-2xl border-4 border-white shadow-2xl object-cover">
                    @else
                        <div class="w-32 h-32 rounded-2xl border-4 border-white shadow-2xl bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 text-center sm:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $profile->user->name }}</h1>
                        <p class="text-gray-600 mb-3">{{ $profile->secteur_activite }}</p>
                        <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold">
                                {{ $profile->category->name }}
                            </span>
                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                Actif
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="p-3 rounded-xl bg-primary-600 text-white hover:bg-primary-700 transition-all shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </button>
                        <button class="p-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 font-medium">Localisation</p>
                            <p class="font-semibold text-gray-900">{{ $profile->localisation }}</p>
                        </div>
                    </div>

                    @if($profile->telephone)
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 font-medium">Téléphone</p>
                            <p class="font-semibold text-gray-900">{{ $profile->telephone }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 font-medium">Email</p>
                            <p class="font-semibold text-gray-900 break-all">{{ $profile->user->email }}</p>
                        </div>
                    </div>
                </div>

                @if($profile->bio)
                <div class="mb-8 p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        À propos
                    </h3>
                    <p class="text-gray-700 leading-relaxed">{{ $profile->bio }}</p>
                </div>
                @endif

                @if($profile->documents->count() > 0)
                <div class="p-6 rounded-xl bg-gradient-to-br from-primary-50 to-accent-50 border border-primary-100">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Documents
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($profile->documents as $doc)
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                               class="flex items-center gap-3 p-4 bg-white rounded-xl hover:shadow-md transition-all border border-gray-200 group">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-primary-200 transition-colors">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium truncate">{{ $doc->file_name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
