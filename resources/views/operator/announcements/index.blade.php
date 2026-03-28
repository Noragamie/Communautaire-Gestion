@extends('layouts.app')
@section('title', 'Annonces - CommunePro')

@section('content')
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 rounded-2xl border border-gray-100">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Annonces</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base max-w-2xl">
                    @if($locked ?? false)
                        Consultez ici toutes les annonces publiées sur la plateforme, une fois votre profil validé.
                    @else
                        Publications officielles de toutes les communes, gérées par l’équipe d’administration.
                        
                    @endif
                </p>
            </div>
            <!-- @if(! ($locked ?? false) && isset($announcements) && $announcements->isNotEmpty())
                <a href="{{ route('operator.profile.show') }}"
                   class="inline-flex items-center justify-center text-sm font-semibold text-primary-600 hover:text-primary-700 shrink-0">
                    Mon profil
                </a>
            @endif -->
        </div>

        @if($locked ?? false)
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-8 sm:p-10">
                <div class="max-w-lg mx-auto text-center">
                    <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-yellow-900 mb-2">Accès restreint</h2>
                    <p class="text-sm text-yellow-800 leading-relaxed">
                        Les annonces sont réservées aux membres dont le profil a été approuvé par l’administration.
                    </p>
                    <a href="{{ route('operator.profile.show') }}"
                       class="inline-flex mt-6 items-center justify-center rounded-xl bg-primary-600 text-white px-6 py-3 text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm">
                        Voir mon profil
                    </a>
                </div>
            </div>
        @elseif($announcements->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 sm:p-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 mb-2">Aucune annonce pour le moment</h2>
                <p class="text-gray-600 text-sm max-w-md mx-auto">
                    Il n’y a pas encore d’annonce publiée sur la plateforme. Revenez plus tard.
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($announcements as $announcement)
                    <article class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col md:flex-row hover:shadow-md transition-shadow group">
                        <div class="md:w-2/5 lg:w-1/3 h-52 md:h-auto md:min-h-[200px] shrink-0 overflow-hidden bg-gray-100">
                            @if($announcement->image)
                                <img src="{{ asset('storage/'.$announcement->image) }}"
                                     alt=""
                                     class="w-full h-full object-cover md:min-h-[200px] hover:scale-[1.02] transition-transform duration-300">
                            @else
                                <div class="w-full h-full min-h-[13rem] bg-gradient-to-br from-primary-50 to-accent-50 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 p-6 sm:p-8 min-w-0 flex flex-col justify-center">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-100">
                                    Annonce
                                </span>
                                @if($announcement->commune)
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $announcement->commune->name }}
                                    </span>
                                @endif
                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $announcement->published_at?->format('d/m/Y à H:i') }}
                                </span>
                                @if($announcement->author)
                                    <span class="text-sm text-gray-500">
                                        · {{ $announcement->author->name }}
                                    </span>
                                @endif
                            </div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition-colors">
                                <a href="{{ route('operator.announcements.show', $announcement) }}" class="focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 rounded">
                                    {{ $announcement->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 leading-relaxed line-clamp-3 mb-4">
                                {{ \App\Support\Markdown::excerpt($announcement->content, 240) }}
                            </p>
                            <a href="{{ route('operator.announcements.show', $announcement) }}"
                               class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700">
                                Lire la suite
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach

                @if($announcements->hasPages())
                    <div class="mt-8">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
