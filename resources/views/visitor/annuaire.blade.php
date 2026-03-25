@extends('layouts.app')

@section('title', 'Annuaire des membres - The Collective')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold">Annuaire des membres</h1>
            <p class="text-gray-500 mt-1">Connectez-vous avec les esprits créatifs et techniques de la communauté.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold hover:bg-white/10 transition-all">Tous</button>
            <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold hover:bg-white/10 transition-all">Design</button>
            <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold hover:bg-white/10 transition-all">Engineering</button>
            <button class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold hover:bg-white/10 transition-all">Strategy</button>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-6">
        @foreach($categories as $category)
            @php 
                // Simulation de membres pour le design (dans un vrai cas on bouclerait sur les profils)
                $colors = ['bg-accent-blue', 'bg-accent-purple', 'bg-emerald-500', 'bg-orange-500'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            <div class="glass-card p-6 rounded-[2rem] text-center group hover:border-accent-blue/30 transition-all cursor-pointer">
                <div class="relative inline-block mb-4">
                    <div class="w-20 h-20 rounded-[1.5rem] {{ $color }}/20 flex items-center justify-center text-2xl font-bold {{ str_replace('bg-', 'text-', $color) }}">
                        {{ substr($category->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-dark-900 border-2 border-white/10 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    </div>
                </div>
                <h3 class="font-bold text-lg">{{ $category->name }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $category->profiles_count }} Membres actifs</p>
                
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('category.show', $category) }}" class="flex-1 py-2 rounded-xl bg-accent-blue text-white text-[10px] font-bold uppercase tracking-wider hover:bg-accent-blue/90 transition-all">Voir Profils</a>
                    <button class="p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </button>
                </div>
            </div>
        @endforeach

        <!-- Placeholder pour inviter -->
        <div class="glass-card p-6 rounded-[2rem] border-dashed border-white/10 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-full border-2 border-dashed border-white/10 flex items-center justify-center text-gray-500 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <p class="text-sm font-bold text-gray-400">Vous ne trouvez pas qui vous cherchez ?</p>
            <p class="text-[10px] text-gray-600 mt-1">Essayez d'élargir vos filtres ou invitez un nouveau membre.</p>
            <button class="mt-6 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-wider hover:bg-white/10 transition-all">Inviter un membre</button>
        </div>
    </div>
</div>
@endsection
