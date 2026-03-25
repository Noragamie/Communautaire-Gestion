@extends('layouts.app')

@section('title', 'Tableau de Bord - Communautaire')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Bienvenue @auth{{ auth()->user()->name }}@else à Communautaire@endauth</h1>
            <p class="text-gray-600 mt-2 text-lg">Voici un aperçu de votre communauté et des dernières activités.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-4 gap-6">
        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Membres Actifs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">1,284</p>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 12% ce mois</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Événements</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">342</p>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 8% ce mois</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Discussions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">568</p>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 24% ce mois</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-6 shadow-soft hover:shadow-medium transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Engagement</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">87%</p>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 5% ce mois</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="col-span-2 space-y-8">
            <!-- Featured Event -->
            <div class="card-glass rounded-2xl overflow-hidden shadow-soft hover:shadow-medium transition-all group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=1000" alt="Event" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>
                <div class="p-8">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-600 text-xs font-bold">Événement à venir</span>
                        <span class="text-xs text-gray-500">14 Mars 2024</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Conférence Annuelle 2024</h3>
                    <p class="text-gray-600 mb-6">Rejoignez-nous pour notre plus grand événement de l'année. Découvrez les dernières tendances, rencontrez des experts et connectez-vous avec la communauté.</p>
                    <button class="btn-primary text-white px-6 py-3 rounded-lg font-medium hover:shadow-medium transition-all">S'inscrire maintenant</button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card-glass rounded-2xl p-8 shadow-soft">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Activités Récentes</h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 pb-4 border-b border-blue-100">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Nouveau membre inscrit</p>
                            <p class="text-sm text-gray-600 mt-1">Sarah Chen a rejoint la communauté</p>
                            <p class="text-xs text-gray-500 mt-2">Il y a 2 heures</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 pb-4 border-b border-blue-100">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Événement approuvé</p>
                            <p class="text-sm text-gray-600 mt-1">Workshop: Introduction à React</p>
                            <p class="text-xs text-gray-500 mt-2">Il y a 4 heures</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Nouvelle discussion</p>
                            <p class="text-sm text-gray-600 mt-1">"Meilleures pratiques pour les APIs"</p>
                            <p class="text-xs text-gray-500 mt-2">Il y a 6 heures</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-8">
            <!-- Upcoming Events -->
            <div class="card-glass rounded-2xl p-6 shadow-soft">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Prochains Événements</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-all cursor-pointer">
                        <p class="font-semibold text-gray-900 text-sm">Founders Coffee</p>
                        <p class="text-xs text-gray-600 mt-1">14 Mars • 09:00</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-all cursor-pointer">
                        <p class="font-semibold text-gray-900 text-sm">Tech Talk: Future of AI</p>
                        <p class="text-xs text-gray-600 mt-1">16 Mars • 18:30</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-all cursor-pointer">
                        <p class="font-semibold text-gray-900 text-sm">Monthly Town Hall</p>
                        <p class="text-xs text-gray-600 mt-1">21 Mars • 11:00</p>
                    </div>
                </div>
            </div>

            <!-- Top Members -->
            <div class="card-glass rounded-2xl p-6 shadow-soft">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Membres Actifs</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg btn-primary flex items-center justify-center text-white font-bold">M</div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm">Marcus Thorne</p>
                            <p class="text-xs text-gray-600">Product Designer</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">A</div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm">Anais Vallet</p>
                            <p class="text-xs text-gray-600">Fullstack Dev</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-300 to-blue-500 flex items-center justify-center text-white font-bold">J</div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm">Julien Marc</p>
                            <p class="text-xs text-gray-600">Visual Strategist</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Box -->
            <div class="btn-primary rounded-2xl p-6 text-white shadow-medium hover:shadow-lg transition-all">
                <h3 class="text-lg font-bold mb-2">Prêt à explorer ?</h3>
                <p class="text-sm text-blue-100 mb-4">Découvrez tous les membres et événements de la communauté.</p>
                <button class="w-full bg-white text-blue-600 font-bold py-2 rounded-lg hover:bg-blue-50 transition-all">Commencer</button>
            </div>
        </div>
    </div>
</div>
@endsection
