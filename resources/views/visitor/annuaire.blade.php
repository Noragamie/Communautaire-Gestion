@extends('layouts.app')

@section('title', 'Annuaire des Membres - Communautaire')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Annuaire des Membres</h1>
            <p class="text-gray-600 mt-2 text-lg">Connectez-vous avec les meilleurs talents de la communauté.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-600 text-sm font-medium hover:bg-blue-100 transition-all">Tous</button>
            <button class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">Design</button>
            <button class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">Engineering</button>
            <button class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">Strategy</button>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-4 gap-6">
        @foreach($categories as $category)
            @php 
                $colors = [
                    'bg-blue-100' => 'text-blue-600',
                    'bg-purple-100' => 'text-purple-600',
                    'bg-emerald-100' => 'text-emerald-600',
                    'bg-orange-100' => 'text-orange-600',
                ];
                $colorPairs = array_keys($colors);
                $colorPair = $colorPairs[$loop->index % count($colorPairs)];
                $textColor = $colors[$colorPair];
            @endphp
            <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all group cursor-pointer">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg {{ $colorPair }} flex items-center justify-center {{ $textColor }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-all transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
                <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition-all">{{ $category->name }}</h3>
                <p class="text-gray-600 text-sm mt-2">{{ $category->profiles_count }} Membres actifs</p>
                <a href="{{ route('category.show', $category) }}" class="mt-6 block w-full py-2 rounded-lg bg-blue-50 text-blue-600 text-sm font-bold text-center hover:bg-blue-100 transition-all">
                    Voir les Profils
                </a>
            </div>
        @endforeach

        <!-- Add Member CTA -->
        <div class="card-glass rounded-2xl p-6 shadow-soft border-2 border-dashed border-blue-200 hover:border-blue-400 transition-all flex flex-col items-center justify-center text-center cursor-pointer group">
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-all">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-all">Ajouter une Catégorie</h3>
            <p class="text-gray-600 text-sm mt-2">Créez une nouvelle catégorie de membres</p>
        </div>
    </div>

    <!-- Members Showcase -->
    <div class="card-glass rounded-2xl p-8 shadow-soft">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Membres en Vedette</h2>
        <div class="grid grid-cols-4 gap-6">
            <div class="group cursor-pointer">
                <div class="relative mb-4 overflow-hidden rounded-xl">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=400" alt="Member" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end">
                        <button class="w-full py-2 btn-primary text-white font-medium">Voir le Profil</button>
                    </div>
                </div>
                <h3 class="font-bold text-gray-900">Sarah Chen</h3>
                <p class="text-sm text-blue-600 font-medium">Product Designer</p>
                <p class="text-xs text-gray-600 mt-1">Spécialisée en UX/UI</p>
            </div>

            <div class="group cursor-pointer">
                <div class="relative mb-4 overflow-hidden rounded-xl">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=400" alt="Member" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end">
                        <button class="w-full py-2 btn-primary text-white font-medium">Voir le Profil</button>
                    </div>
                </div>
                <h3 class="font-bold text-gray-900">David Rossi</h3>
                <p class="text-sm text-blue-600 font-medium">Fullstack Developer</p>
                <p class="text-xs text-gray-600 mt-1">Expert React & Node.js</p>
            </div>

            <div class="group cursor-pointer">
                <div class="relative mb-4 overflow-hidden rounded-xl">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=400" alt="Member" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end">
                        <button class="w-full py-2 btn-primary text-white font-medium">Voir le Profil</button>
                    </div>
                </div>
                <h3 class="font-bold text-gray-900">Elena Rodriguez</h3>
                <p class="text-sm text-blue-600 font-medium">Product Manager</p>
                <p class="text-xs text-gray-600 mt-1">Stratégie & Growth</p>
            </div>

            <div class="group cursor-pointer">
                <div class="relative mb-4 overflow-hidden rounded-xl">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=400" alt="Member" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end">
                        <button class="w-full py-2 btn-primary text-white font-medium">Voir le Profil</button>
                    </div>
                </div>
                <h3 class="font-bold text-gray-900">Marcus Thorne</h3>
                <p class="text-sm text-blue-600 font-medium">CTO</p>
                <p class="text-xs text-gray-600 mt-1">Architecture & DevOps</p>
            </div>
        </div>
    </div>
</div>
@endsection
