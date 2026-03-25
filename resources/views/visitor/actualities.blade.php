@extends('layouts.app')

@section('title', 'Événements - Communautaire')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Événements & Actualités</h1>
            <p class="text-gray-600 mt-2 text-lg">Découvrez et participez aux prochains événements de la communauté.</p>
        </div>
        <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold hover:shadow-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Créer un Événement
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 border-b border-blue-100 pb-4">
        <button class="px-4 py-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-all">Tous</button>
        <button class="px-4 py-2 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">À venir</button>
        <button class="px-4 py-2 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">Passés</button>
        <button class="px-4 py-2 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">Mes Événements</button>
    </div>

    <!-- Events List -->
    <div class="space-y-6">
        @forelse($actualities as $actuality)
        <div class="card-glass rounded-2xl overflow-hidden shadow-soft hover:shadow-medium transition-all group">
            <div class="flex flex-col md:flex-row">
                <!-- Image -->
                <div class="md:w-1/3 h-64 md:h-auto overflow-hidden">
                    @if($actuality->image)
                        <img src="{{ asset('storage/'.$actuality->image) }}" alt="{{ $actuality->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                            <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="md:w-2/3 p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-600 text-xs font-bold">Événement</span>
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $actuality->published_at->format('d M Y') }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $actuality->title }}</h2>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                            {!! strip_tags($actuality->content) !!}
                        </p>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex -space-x-3">
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-300"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-400"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-500"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white btn-primary flex items-center justify-center text-white text-[10px] font-bold">+12</div>
                        </div>
                        <button class="px-6 py-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-all">
                            S'inscrire
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card-glass rounded-2xl p-20 shadow-soft text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun événement</h3>
            <p class="text-gray-600 mb-8">Il n'y a actuellement aucun événement programmé. Revenez bientôt !</p>
        </div>
        @endforelse
    </div>

    <!-- Newsletter Section -->
    <div class="card-glass rounded-2xl p-8 shadow-soft border-2 border-blue-200">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 btn-primary rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Newsletter</h3>
                <p class="text-sm text-gray-600">Recevez les actualités par email</p>
            </div>
        </div>
        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex gap-4">
            @csrf
            <input type="email" name="email" placeholder="votre@email.com" required class="flex-1 px-4 py-3 border border-blue-200 rounded-lg focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-bold hover:shadow-medium transition-all">
                S'abonner
            </button>
        </form>
    </div>
</div>
@endsection
