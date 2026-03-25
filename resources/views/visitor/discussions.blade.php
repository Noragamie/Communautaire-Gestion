@extends('layouts.app')

@section('title', 'Discussions - Communautaire')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Discussions & Conversations</h1>
            <p class="text-gray-600 mt-2 text-lg">Partagez vos idées, posez vos questions et apprenez de la communauté.</p>
        </div>
        <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold hover:shadow-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle Discussion
        </button>
    </div>

    <!-- Categories -->
    <div class="grid grid-cols-3 gap-6">
        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all group cursor-pointer">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-all">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-all transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition-all">Idées & Projets</h3>
            <p class="text-gray-600 text-sm mt-2">24 Discussions actives</p>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all group cursor-pointer">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-all">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-all transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <h3 class="font-bold text-lg text-gray-900 group-hover:text-green-600 transition-all">Support Général</h3>
            <p class="text-gray-600 text-sm mt-2">15 Discussions actives</p>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all group cursor-pointer">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-all">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-all transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <h3 class="font-bold text-lg text-gray-900 group-hover:text-purple-600 transition-all">Inspirations</h3>
            <p class="text-gray-600 text-sm mt-2">42 Discussions actives</p>
        </div>
    </div>

    <!-- Active Discussions -->
    <div class="card-glass rounded-2xl p-8 shadow-soft">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Discussions Actives</h2>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-all">Plus récentes</button>
                <button class="px-4 py-2 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">Plus populaires</button>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center gap-6 p-4 bg-blue-50 rounded-lg border border-blue-200 hover:border-blue-300 transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-lg bg-blue-200 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-all">Best practices for Scalable Architecture</h4>
                    <p class="text-xs text-gray-600 mt-1">Lancé par Sarah Chen • Il y a 2 heures</p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-300"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-400"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white btn-primary flex items-center justify-center text-white text-[8px] font-bold">+5</div>
                </div>
            </div>

            <div class="flex items-center gap-6 p-4 bg-blue-50 rounded-lg border border-blue-200 hover:border-blue-300 transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-lg bg-green-200 flex items-center justify-center text-green-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-all">API Integration: Modernizing Legacy Systems</h4>
                    <p class="text-xs text-gray-600 mt-1">Lancé par David Rossi • Il y a 5 heures</p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-300"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-400"></div>
                </div>
            </div>

            <div class="flex items-center gap-6 p-4 bg-blue-50 rounded-lg border border-blue-200 hover:border-blue-300 transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-lg bg-purple-200 flex items-center justify-center text-purple-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-all">Social Mixer: Remote Team Building</h4>
                    <p class="text-xs text-gray-600 mt-1">Lancé par Elena Rodriguez • Hier</p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-300"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-400"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-500"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-white btn-primary flex items-center justify-center text-white text-[8px] font-bold">+21</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
