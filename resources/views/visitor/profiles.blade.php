@extends('layouts.guest')
@section('title', 'Profils - CommunePro')

@section('content')
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Tous les profils</h1>
            <p class="text-gray-600">Découvrez tous les membres de la communauté CommunePro</p>
        </div>

        <!-- Search & Filters -->
        <div class="mb-8">
            <form method="GET" class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Rechercher par nom, secteur, localisation..."
                               class="input-modern">
                    </div>
                    
                    <select name="category" class="input-modern">
                        <option value="">Toutes les catégories</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Profiles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $profiles = \App\Models\Profile::approved()
                    ->with(['user', 'category'])
                    ->when(request('search'), function($query) {
                        $search = request('search');
                        $query->where(function($q) use ($search) {
                            $q->where('secteur_activite', 'like', "%{$search}%")
                              ->orWhere('localisation', 'like', "%{$search}%")
                              ->orWhereHas('user', function($uq) use ($search) {
                                  $uq->where('name', 'like', "%{$search}%");
                              });
                        });
                    })
                    ->when(request('category'), function($query) {
                        $query->where('category_id', request('category'));
                    })
                    ->latest()
                    ->paginate(12);
            @endphp

            @forelse($profiles as $profile)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="text-center">
                        @if($profile->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" 
                                 alt="{{ $profile->user->name }}" 
                                 class="w-20 h-20 rounded-xl mx-auto object-cover mb-4 border-2 border-gray-100">
                        @else
                            <div class="w-20 h-20 rounded-xl mx-auto bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center mb-4">
                                <span class="text-2xl font-bold text-white">
                                    {{ substr($profile->user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $profile->user->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $profile->secteur_activite }}</p>
                        
                        <div class="flex items-center justify-center gap-1 text-xs text-gray-500 mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $profile->localisation }}</span>
                        </div>
                        
                        @if($profile->category)
                            <span class="inline-block px-3 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-full mb-4">
                                {{ $profile->category->name }}
                            </span>
                        @endif
                        
                        <a href="{{ route('profiles.show', $profile) }}" 
                           class="block w-full px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors text-sm">
                            Voir le profil
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 bg-white border border-gray-200 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun profil trouvé</h3>
                    <p class="text-gray-600">Essayez de modifier vos critères de recherche</p>
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
