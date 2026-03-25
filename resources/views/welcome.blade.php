@extends('layouts.app')

@section('title', 'Tableau de Bord - The Collective')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold">Bienvenue @auth, {{ auth()->user()->name }} @else à Julianne @endauth</h1>
            <p class="text-gray-500 mt-1">Voici un aperçu de l'activité de votre communauté ce matin.</p>
        </div>
        <div class="flex gap-4">
            <div class="glass-card px-6 py-3 rounded-2xl flex flex-col items-center">
                <span class="text-2xl font-bold text-accent-blue">1,284</span>
                <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Membres</span>
            </div>
            <div class="glass-card px-6 py-3 rounded-2xl flex flex-col items-center">
                <span class="text-2xl font-bold text-accent-purple">342</span>
                <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Événements</span>
            </div>
            <div class="glass-card px-6 py-3 rounded-2xl flex flex-col items-center">
                <span class="text-2xl font-bold text-white">12</span>
                <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold">Groupes</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-8">
        <!-- Main Feed -->
        <div class="col-span-2 space-y-8">
            <section>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Actualités</h2>
                    <a href="{{ route('actualities') }}" class="text-xs text-accent-blue font-bold uppercase tracking-wider">Voir tout</a>
                </div>
                
                <!-- Hero News Card -->
                <div class="glass-card rounded-3xl overflow-hidden group cursor-pointer">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&q=80&w=2070" alt="Innovation" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent"></div>
                        <div class="absolute bottom-6 left-6">
                            <span class="px-3 py-1 rounded-full bg-accent-blue text-[10px] font-bold uppercase tracking-wider mb-2 inline-block">Innovation</span>
                            <h3 class="text-2xl font-bold">Expansion de l'Innovation Lab</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Notre espace dédié à la créativité double sa surface dès le mois prochain. De nouveaux équipements de prototypage et des zones de détente dynamiques sont au rendez-vous.
                        </p>
                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-700"></div>
                                <span class="text-xs font-medium text-gray-300">Par Équipe Design</span>
                            </div>
                            <button class="px-4 py-2 rounded-xl border border-white/10 text-xs font-bold hover:bg-white/5 transition-all">Lire l'article</button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-2 gap-8">
                <div class="glass-card p-6 rounded-3xl">
                    <h3 class="font-bold mb-4">Portrait de membre</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent-blue to-accent-purple"></div>
                        <div>
                            <p class="font-bold">Thomas Meyer</p>
                            <p class="text-xs text-gray-500">Architecte de Solutions</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mt-4 italic">"The Collective m'a permis de trouver des partenaires pour mon dernier projet open-source."</p>
                </div>
                <div class="glass-card p-6 rounded-3xl">
                    <h3 class="font-bold mb-4">Atelier Bien-être</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent-purple/20 flex items-center justify-center text-accent-purple">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold">Yoga & Méditation</p>
                            <p class="text-xs text-gray-500">Demain à 14h</p>
                        </div>
                    </div>
                    <button class="w-full mt-6 py-2 rounded-xl bg-white/5 text-xs font-bold hover:bg-white/10 transition-all">S'inscrire</button>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-8">
            <section>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Événements</h2>
                    <a href="{{ route('actualities') }}" class="text-xs text-accent-blue font-bold uppercase tracking-wider">Tout voir</a>
                </div>
                <div class="space-y-4">
                    <div class="glass-card p-4 rounded-2xl flex gap-4 items-start">
                        <div class="bg-accent-blue/10 text-accent-blue p-2 rounded-xl text-center min-w-[50px]">
                            <span class="block text-lg font-bold">14</span>
                            <span class="text-[10px] font-bold uppercase">Mar</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Founders Coffee Social</p>
                            <p class="text-xs text-gray-500 mt-1">09:00 - 10:30 • Le Perchoir</p>
                        </div>
                    </div>
                    <div class="glass-card p-4 rounded-2xl flex gap-4 items-start">
                        <div class="bg-accent-purple/10 text-accent-purple p-2 rounded-xl text-center min-w-[50px]">
                            <span class="block text-lg font-bold">16</span>
                            <span class="text-[10px] font-bold uppercase">Mar</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Tech Talk: Future of AI</p>
                            <p class="text-xs text-gray-500 mt-1">18:30 - 20:00 • Auditorium</p>
                        </div>
                    </div>
                    <div class="glass-card p-4 rounded-2xl flex gap-4 items-start">
                        <div class="bg-white/5 text-gray-400 p-2 rounded-xl text-center min-w-[50px]">
                            <span class="block text-lg font-bold">21</span>
                            <span class="text-[10px] font-bold uppercase">Mar</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Monthly Town Hall</p>
                            <p class="text-xs text-gray-500 mt-1">11:00 - 12:00 • Main Hall</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="glass-card p-8 rounded-[2rem] bg-gradient-to-br from-accent-blue to-accent-purple relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold leading-tight">Prêt pour le prochain Hackathon ?</h3>
                    <p class="text-sm text-white/70 mt-4">Inscrivez-vous dès maintenant pour participer à la compétition de l'année.</p>
                    <button class="mt-8 px-6 py-3 rounded-2xl bg-white text-dark-900 font-bold text-sm hover:shadow-xl transition-all">En savoir plus</button>
                </div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection
