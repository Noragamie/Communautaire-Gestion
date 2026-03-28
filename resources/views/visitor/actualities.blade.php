@extends('layouts.app')
@section('title', 'Actualités - CommunePro')

@php
    $keepCommuneOnly = array_filter(
        ['commune_id' => request('commune_id')],
        fn ($v) => $v !== null && $v !== ''
    );
@endphp

@section('content')
<div class="min-w-0">
    {{-- Hero pleine largeur (même esprit que l’annuaire) --}}
    <section class="relative w-screen max-w-[100vw] ml-[calc(50%-50vw)] overflow-x-hidden bg-[#2563EB] text-white">
        <div class="relative max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-14 lg:pt-16 lg:pb-20 w-full">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white mb-3">
                    Actualités
                </h1>
                <p class="text-lg text-white/90">
                    Infos et nouvelles de la communauté. Utilisez la colonne de gauche pour filtrer par mois ou par commune.
                </p>
            </div>
        </div>
    </section>

    {{-- Liste + colonne filtres --}}
    <section class="border-t border-gray-100 bg-gray-50 py-10 lg:py-12">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 lg:items-start">
                {{-- Sidebar filtres --}}
                <aside class="w-full lg:w-72 shrink-0 space-y-6">
                    @if($months->isNotEmpty())
                        <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Mois</h2>
                                <a href="{{ route('actualities') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Tout effacer</a>
                            </div>
                            <ul class="space-y-1 max-h-[min(360px,50vh)] overflow-y-auto pr-1">
                                <li>
                                    <a href="{{ route('actualities', $keepCommuneOnly) }}"
                                       class="block rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ ! request('month') ? 'bg-primary-50 text-primary-800' : 'text-gray-700 hover:bg-gray-50' }}">
                                        Toutes les périodes
                                    </a>
                                </li>
                                @foreach($months as $month)
                                    <li>
                                        <a href="{{ route('actualities', array_merge($keepCommuneOnly, ['month' => $month['value']])) }}"
                                           class="block rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request('month') === $month['value'] ? 'bg-primary-50 text-primary-800' : 'text-gray-700 hover:bg-gray-50' }}">
                                            {{ $month['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Commune</h2>
                        <form method="GET" action="{{ route('actualities') }}">
                            @if(request('month'))
                                <input type="hidden" name="month" value="{{ request('month') }}">
                            @endif
                            <label for="actualities-commune" class="sr-only">Commune</label>
                            <select id="actualities-commune" name="commune_id" onchange="this.form.submit()"
                                    class="w-full rounded-xl border-gray-300 text-sm text-gray-800 focus:border-primary-500 focus:ring-primary-500 py-2.5 pl-3 pr-8">
                                <option value="">Toutes les communes</option>
                                @foreach($communes as $commune)
                                    <option value="{{ $commune->id }}" @selected((string) request('commune_id') === (string) $commune->id)>{{ $commune->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        <p class="text-xs text-gray-500 mt-2">Actualités liées à une commune sur la plateforme.</p>
                    </div>

                    <p class="text-xs text-gray-500 px-1">
                        Les filtres s’appliquent dès que vous choisissez une commune ; les mois sont des liens instantanés.
                    </p>
                </aside>

                {{-- Contenu principal --}}
                <div class="flex-1 min-w-0 space-y-6">
                    @forelse($actualities as $actuality)
                        <article class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-lg transition-shadow">
                            <div class="md:w-1/3 h-56 md:h-auto md:min-h-[220px] overflow-hidden shrink-0">
                                @if($actuality->image)
                                    <img src="{{ asset('storage/'.$actuality->image) }}"
                                         alt="{{ $actuality->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full min-h-[14rem] bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="md:w-2/3 p-6 sm:p-8 flex flex-col justify-center min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold">
                                        Actualité
                                    </span>
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $actuality->published_at->format('d/m/Y à H:i') }}
                                    </span>
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">{{ $actuality->title }}</h2>
                                <p class="text-gray-600 leading-relaxed line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($actuality->content), 280) }}
                                </p>
                            </div>
                        </article>
                    @empty
                        <div class="bg-white rounded-2xl border border-gray-200 p-12 sm:p-16 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Aucune actualité pour l’instant</h3>
                            <p class="text-gray-600 max-w-md mx-auto">
                                @if(request('month') || request('commune_id'))
                                    Élargissez vos critères ou
                                    <a href="{{ route('actualities') }}" class="text-primary-600 font-semibold hover:text-primary-700">réinitialiser les filtres</a>.
                                @else
                                    Revenez bientôt pour découvrir les prochaines publications.
                                @endif
                            </p>
                        </div>
                    @endforelse

                    @if($actualities->hasPages())
                        <div class="mt-10">
                            {{ $actualities->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Newsletter (disposition 2 colonnes, style maquette, DA CommunePro) --}}
            <div class="mt-14 rounded-3xl bg-primary-600 p-8 sm:p-10 lg:p-12 shadow-xl border border-primary-700/40">
                <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 lg:items-center">
                    <div class="text-left">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mb-4">
                            Abonnez-vous à notre newsletter
                        </h2>
                        <p class="text-white/90 text-base sm:text-lg leading-relaxed">
                            Recevez les actualités de la communauté et les informations utiles pour suivre la vie de la plateforme CommunePro.
                        </p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white/80 mb-3">Ne rien manquer</p>
                        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-3">
                            @csrf
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2">
                                <label class="sr-only" for="newsletter-email">Adresse e-mail</label>
                                <input type="email"
                                       id="newsletter-email"
                                       name="email"
                                       placeholder="Votre adresse e-mail"
                                       required
                                       autocomplete="email"
                                       class="min-w-0 flex-1 rounded-full border border-white/25 bg-white/15 px-5 py-3.5 text-sm text-white placeholder:text-white/55 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-white/40 focus:border-white/40 transition-shadow">
                                <button type="submit"
                                        class="shrink-0 rounded-full bg-white px-7 py-3.5 text-sm font-bold text-primary-700 shadow-md hover:bg-primary-50 transition-colors">
                                    S’abonner
                                </button>
                            </div>
                            <p class="text-xs sm:text-sm text-white/70 leading-relaxed">
                                En vous abonnant, vous acceptez notre
                                <a href="#" class="underline decoration-white/40 underline-offset-2 hover:text-white hover:decoration-white">politique de confidentialité</a>.
                                Vous pouvez vous désabonner à tout moment.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
