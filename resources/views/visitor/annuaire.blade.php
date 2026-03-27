@extends('layouts.app')
@section('title', 'Annuaire des membres - CommunePro')

@php
    $selectedCategoryIds = array_values(array_filter(array_map('intval', (array) request('categories', []))));
    if ($selectedCategoryIds === [] && request()->filled('category')) {
        $selectedCategoryIds = [(int) request('category')];
    }
    $badgePalette = [
        'bg-violet-100 text-violet-800',
        'bg-emerald-100 text-emerald-800',
        'bg-amber-100 text-amber-800',
        'bg-sky-100 text-sky-800',
        'bg-rose-100 text-rose-800',
        'bg-indigo-100 text-indigo-800',
        'bg-teal-100 text-teal-800',
    ];
@endphp

@section('content')
<form method="GET" action="{{ route('annuaire') }}" class="min-w-0">
    <!-- Hero : fond #2563EB, pleine largeur viewport (sort du max-w du layout) -->
    <section class="relative w-screen max-w-[100vw] ml-[calc(50%-50vw)] overflow-x-hidden bg-[#2563EB] text-white">
        <div class="relative max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-14 lg:pt-16 lg:pb-20 w-full">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white mb-3">
                    Trouvez les bons profils
                </h1>
                <p class="text-lg text-white/90 mb-8">
                    Recherchez par nom, secteur ou ville parmi les membres approuvés de la communauté.
                </p>
            </div>

            <div class="max-w-4xl">
                <div class="flex flex-col sm:flex-row sm:items-stretch gap-2 sm:gap-0 rounded-2xl sm:rounded-full bg-white p-2 shadow-xl shadow-black/20">
                    <label class="flex flex-1 items-center gap-3 min-h-[3rem] px-4 text-gray-500">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="search" name="search" value="{{ request('search') }}"
                               placeholder="Nom, secteur, compétences…"
                               class="w-full min-w-0 border-0 bg-transparent text-gray-900 placeholder:text-gray-400 focus:ring-0 text-sm sm:text-base">
                    </label>
                    <div class="hidden sm:block w-px bg-gray-200 self-stretch my-2"></div>
                    <label class="flex flex-1 sm:max-w-[220px] items-center gap-3 min-h-[3rem] px-4 text-gray-500 border-t border-gray-100 sm:border-0 pt-2 sm:pt-0">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input type="text" name="location" value="{{ request('location') }}"
                               placeholder="Ville ou région"
                               class="w-full min-w-0 border-0 bg-transparent text-gray-900 placeholder:text-gray-400 focus:ring-0 text-sm sm:text-base">
                    </label>
                    <button type="submit" class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-full bg-white text-[#2563EB] hover:bg-blue-50 font-semibold px-8 py-3 text-sm sm:text-base transition-colors shadow-sm">
                        Rechercher
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Corps : filtres + résultats -->
    <section class="py-10 lg:py-12  border-t border-gray-100">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 lg:items-start">
                <!-- Sidebar filtres -->
                <aside class="w-full lg:w-72 shrink-0 space-y-6">
                    <div class="rounded-2xl bg-white border border-gray-200 p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Catégories</h2>
                            <a href="{{ route('annuaire') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Tout effacer</a>
                        </div>
                        <ul class="space-y-3 max-h-[min(420px,55vh)] overflow-y-auto pr-1">
                            @foreach($categories as $cat)
                                <li>
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                               @checked(in_array($cat->id, $selectedCategoryIds, true))
                                               class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                        <span class="flex-1 text-sm text-gray-700 group-hover:text-gray-900">
                                            <span class="font-medium">{{ $cat->name }}</span>
                                            <span class="text-gray-400 font-normal"> · {{ $cat->profiles_count }}</span>
                                        </span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <button type="submit" class="mt-5 w-full py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition-colors">
                            Appliquer les filtres
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 px-1">
                        Astuce : combinez la recherche ci-dessus avec une ou plusieurs catégories pour affiner les résultats.
                    </p>
                </aside>

                <!-- Grille profils -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <p class="text-gray-700">
                            <span class="font-semibold text-gray-900">{{ $profiles->total() }}</span>
                            {{ $profiles->total() > 1 ? 'membres' : 'membre' }}
                            @if(request()->anyFilled(['search', 'location', 'categories', 'category']))
                                <span class="text-gray-500 font-normal">correspondant à vos critères</span>
                            @endif
                        </p>
                        <div class="flex items-center gap-2">
                            <label for="annuaire-sort" class="text-sm text-gray-500 whitespace-nowrap">Trier par</label>
                            <select id="annuaire-sort" name="sort" onchange="this.form.submit()"
                                    class="rounded-xl border-gray-200 text-sm text-gray-800 focus:border-primary-500 focus:ring-primary-500 py-2 pl-3 pr-8">
                                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Plus récents</option>
                                <option value="name_asc" @selected(request('sort') === 'name_asc')>Nom (A → Z)</option>
                                <option value="name_desc" @selected(request('sort') === 'name_desc')>Nom (Z → A)</option>
                                <option value="category" @selected(request('sort') === 'category')>Catégorie</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6">
                        @forelse($profiles as $profile)
                            @php
                                $cat = $profile->category;
                                $badgeClass = $cat ? ($badgePalette[$cat->id % count($badgePalette)]) : 'bg-gray-100 text-gray-700';
                            @endphp
                            <article class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all flex flex-col overflow-hidden group">
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        @if($profile->photo)
                                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}"
                                                 class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100 group-hover:ring-primary-100 transition-all">
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center ring-2 ring-gray-100">
                                                <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <a href="{{ route('profiles.show', $profile) }}" class="text-gray-400 hover:text-primary-600 p-1 rounded-lg hover:bg-gray-50 transition-colors" title="Voir le profil">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>

                                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-primary-700 transition-colors mb-1">
                                        <a href="{{ route('profiles.show', $profile) }}" class="focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded">
                                            {{ $profile->user->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-1">{{ $profile->secteur_activite }}</p>

                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if($cat)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ $cat->name }}
                                            </span>
                                        @endif
                                        @if($profile->localisation)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                {{ $profile->localisation }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($profile->bio)
                                        <p class="text-sm text-gray-500 line-clamp-2 flex-1 mb-5">{{ \Illuminate\Support\Str::limit(strip_tags($profile->bio), 140) }}</p>
                                    @else
                                        <div class="flex-1 mb-5"></div>
                                    @endif

                                    <div class="flex items-center justify-between gap-3 pt-4 mt-auto border-t border-gray-100">
                                        <a href="{{ route('profiles.show', $profile) }}"
                                           class="inline-flex items-center justify-center rounded-xl bg-primary-600 text-white text-sm font-semibold px-4 py-2.5 hover:bg-primary-700 transition-colors">
                                            Voir le profil
                                        </a>
                                        <span class="text-xs text-gray-400 flex items-center gap-1 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $profile->updated_at->locale('fr')->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Aucun membre ne correspond</h3>
                                <p class="text-gray-600 mb-6 max-w-md mx-auto">Élargissez la recherche ou retirez certains filtres pour voir plus de profils.</p>
                                <a href="{{ route('annuaire') }}" class="inline-flex items-center justify-center rounded-xl bg-primary-600 text-white font-semibold px-6 py-3 hover:bg-primary-700 transition-colors">
                                    Réinitialiser l’annuaire
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if($profiles->hasPages())
                        <div class="mt-10">
                            {{ $profiles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</form>
@endsection
