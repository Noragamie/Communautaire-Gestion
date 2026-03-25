@extends('layouts.app')

@section('title', 'Annonces')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Annonces</h1>

        @if($locked ?? false)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                <svg class="w-12 h-12 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p class="font-semibold text-yellow-800 mb-2">Accès restreint</p>
                <p class="text-sm text-yellow-700">
                    Les annonces sont réservées aux utilisateurs dont le profil a été approuvé.
                </p>
                <a href="{{ route('operator.profile.show') }}"
                   class="inline-block mt-4 bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 font-medium text-sm transition-all">
                    Voir mon profil
                </a>
            </div>
        @elseif($announcements->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <p class="text-gray-500 font-medium">Aucune annonce pour l'instant</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($announcements as $announcement)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        @if($announcement->image)
                            <img src="{{ asset('storage/' . $announcement->image) }}"
                                 alt="{{ $announcement->title }}"
                                 class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $announcement->title }}</h2>
                            <p class="text-sm text-gray-500 mb-4">
                                {{ $announcement->published_at->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $announcement->content }}</p>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $announcements->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
