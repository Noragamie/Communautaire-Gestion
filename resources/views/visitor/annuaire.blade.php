@extends('layouts.app')
@section('title', 'Annuaire des membres - CommunePro')

@section('content')
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Annuaire des membres</h1>
            <p class="text-gray-600">Connectez-vous avec les esprits créatifs et techniques de la communauté.</p>
        </div>

        <!-- Filters -->
        <div class="mb-8 flex flex-wrap gap-3">
            <a href="{{ route('annuaire') }}" class="px-4 py-2 rounded-lg {{ !request('category') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-sm font-medium transition-colors">
                Tous
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('annuaire', ['category' => $cat->id]) }}" 
                   class="px-4 py-2 rounded-lg {{ request('category') == $cat->id ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-sm font-medium transition-colors">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                @php 
                    $colors = [
                        'from-blue-500 to-blue-600',
                        'from-purple-500 to-purple-600',
                        'from-green-500 to-green-600',
                        'from-orange-500 to-orange-600',
                        'from-pink-500 to-pink-600',
                        'from-indigo-500 to-indigo-600',
                    ];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="relative inline-block mb-4">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $color }} flex items-center justify-center text-2xl font-bold text-white">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->profiles_count }} Membres actifs</p>
                    
                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('category.show', $category) }}" 
                           class="flex-1 py-2 rounded-lg bg-primary-600 text-white text-xs font-bold uppercase tracking-wider hover:bg-primary-700 transition-all">
                            Voir Profils
                        </a>
                        <button class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
