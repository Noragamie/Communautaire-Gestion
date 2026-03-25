@extends('layouts.app')

@section('title', 'Calendrier Communautaire - The Collective')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-center">Calendrier Communautaire</h1>
            <p class="text-gray-500 mt-1 text-center">Découvrez et rejoignez les prochains moments forts de notre collectif.</p>
        </div>
        <button class="px-6 py-3 rounded-2xl bg-accent-blue text-white text-xs font-bold uppercase tracking-wider hover:bg-accent-blue/90 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Créer un Événement
        </button>
    </div>

    <!-- Month Selector -->
    <div class="flex items-center justify-center gap-4">
        <div class="flex bg-white/5 rounded-2xl p-1 border border-white/10">
            <button class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 hover:text-white transition-all">Septembre 2024</button>
            <button class="px-6 py-2 rounded-xl text-xs font-bold bg-white/10 text-white shadow-sm transition-all">Octobre 2024</button>
            <button class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 hover:text-white transition-all">Novembre 2024</button>
            <button class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 hover:text-white transition-all">Décembre 2024</button>
        </div>
        <div class="flex bg-white/5 rounded-2xl p-1 border border-white/10">
            <button class="p-2 rounded-xl text-gray-500 hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </button>
            <button class="p-2 rounded-xl text-gray-500 hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <!-- Events List -->
    <div class="space-y-8">
        @forelse($actualities as $actuality)
        <div class="glass-card rounded-[2.5rem] overflow-hidden flex flex-col md:flex-row group hover:border-accent-blue/30 transition-all">
            <div class="md:w-1/3 h-64 md:h-auto overflow-hidden">
                @if($actuality->image)
                    <img src="{{ asset('storage/'.$actuality->image) }}" alt="{{ $actuality->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-accent-blue/20 to-accent-purple/20 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>
            <div class="md:w-2/3 p-10 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-full bg-accent-blue/10 text-accent-blue text-[10px] font-bold uppercase tracking-wider">Culture Tech</span>
                        <span class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $actuality->published_at->format('H:i') }} - {{ $actuality->published_at->addHours(2)->format('H:i') }} • {{ $actuality->published_at->format('d M Y') }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold mb-4">{{ $actuality->title }}</h2>
                    <p class="text-gray-400 text-sm leading-relaxed line-clamp-3">
                        {!! strip_tags($actuality->content) !!}
                    </p>
                </div>
                
                <div class="mt-8 flex items-center justify-between">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-dark-900 bg-gray-700"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-dark-900 bg-gray-600"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-dark-900 bg-gray-500"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-dark-900 bg-accent-blue flex items-center justify-center text-[10px] font-bold">+12</div>
                    </div>
                    <button class="px-8 py-3 rounded-2xl bg-white/5 border border-white/10 text-xs font-bold uppercase tracking-wider hover:bg-white/10 transition-all">S'inscrire</button>
                </div>
            </div>
        </div>
        @empty
        <div class="glass-card p-20 rounded-[3rem] text-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold">Aucun événement prévu</h3>
            <p class="text-gray-500 mt-2">Revenez plus tard pour découvrir les nouvelles activités.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
