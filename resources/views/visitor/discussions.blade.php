@extends('layouts.app')

@section('title', 'Discussions & Sagesse Collective - The Collective')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold">Conversations & <br>Sagesse Collective</h1>
            <p class="text-gray-500 mt-1">Un espace pour échanger des idées, résoudre des problèmes complexes et cultiver notre savoir commun.</p>
        </div>
        <button class="px-6 py-3 rounded-2xl bg-accent-blue text-white text-xs font-bold uppercase tracking-wider hover:bg-accent-blue/90 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle Discussion
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-3 gap-6">
        <div class="glass-card p-6 rounded-3xl flex flex-col items-center text-center group hover:border-accent-blue/30 transition-all cursor-pointer">
            <div class="w-12 h-12 rounded-2xl bg-accent-blue/10 text-accent-blue flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <h3 class="font-bold text-sm">Idées de Projets</h3>
            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">24 Sujets Actifs</p>
        </div>
        <div class="glass-card p-6 rounded-3xl flex flex-col items-center text-center group hover:border-accent-purple/30 transition-all cursor-pointer">
            <div class="w-12 h-12 rounded-2xl bg-accent-purple/10 text-accent-purple flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="font-bold text-sm">Support Général</h3>
            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">15 Sujets Actifs</p>
        </div>
        <div class="glass-card p-6 rounded-3xl flex flex-col items-center text-center group hover:border-emerald-500/30 transition-all cursor-pointer">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-sm">Inspirations</h3>
            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">42 Sujets Actifs</p>
        </div>
    </div>

    <!-- Active Topics -->
    <section class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold">Sujets Actifs</h2>
            <div class="flex gap-4">
                <button class="text-xs text-accent-blue font-bold uppercase tracking-wider border-b-2 border-accent-blue pb-1">Plus récents</button>
                <button class="text-xs text-gray-500 font-bold uppercase tracking-wider hover:text-white transition-all">Plus populaires</button>
            </div>
        </div>

        <div class="space-y-4">
            <div class="glass-card p-6 rounded-3xl flex items-center gap-6 group hover:bg-white/5 transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-accent-blue/20 flex items-center justify-center text-accent-blue">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold">Best practices for Scalable Architecture</h4>
                    <p class="text-xs text-gray-500 mt-1">Lancé par Sarah Chen • Il y a 2 heures</p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-700"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-600"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-accent-blue flex items-center justify-center text-[8px] font-bold">+5</div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-3xl flex items-center gap-6 group hover:bg-white/5 transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-accent-purple/20 flex items-center justify-center text-accent-purple">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold">API Integration: Modernizing Legacy Systems</h4>
                    <p class="text-xs text-gray-500 mt-1">Lancé par David Rossi • Il y a 5 heures</p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-700"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-600"></div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-3xl flex items-center gap-6 group hover:bg-white/5 transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold">Social Mixer: Remote Team Building</h4>
                    <p class="text-xs text-gray-500 mt-1">Lancé par Elena Rodriguez • Hier</p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-700"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-600"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-gray-500"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-dark-900 bg-accent-blue flex items-center justify-center text-[8px] font-bold">+21</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Promo -->
    <div class="grid grid-cols-2 gap-8">
        <div class="glass-card p-10 rounded-[3rem] bg-gradient-to-br from-accent-blue/20 to-accent-purple/20 border-accent-blue/10">
            <h3 class="text-2xl font-bold">Le Savoir est Partagé</h3>
            <p class="text-sm text-gray-400 mt-4 leading-relaxed">Contribuez à la base de connaissances et devenez un expert reconnu au sein de la communauté.</p>
            <button class="mt-8 px-8 py-3 rounded-2xl bg-accent-blue text-white font-bold text-xs uppercase tracking-wider hover:bg-accent-blue/90 transition-all">Explorer le Wiki</button>
        </div>
        <div class="glass-card p-10 rounded-[3rem]">
            <h3 class="font-bold mb-6">Membres les plus actifs</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-700"></div>
                    <div>
                        <p class="text-sm font-bold">Marcus Thorne</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Product Designer</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-600"></div>
                    <div>
                        <p class="text-sm font-bold">Anais Vallet</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Fullstack Dev</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-500"></div>
                    <div>
                        <p class="text-sm font-bold">Julien Marc</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Visual Strategist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
