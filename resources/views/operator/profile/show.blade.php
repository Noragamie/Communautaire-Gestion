@extends('layouts.app')

@section('title', 'Mon Profil - The Collective')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    @if(!$profile)
        <div class="glass-card p-20 rounded-[3rem] text-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="text-xl font-bold">Profil inexistant</h3>
            <p class="text-gray-500 mt-2 mb-8">Vous n'avez pas encore créé de profil public sur The Collective.</p>
            <a href="{{ route('operator.profile.create') }}" class="px-8 py-4 rounded-2xl bg-accent-blue text-white font-bold hover:bg-accent-blue/90 transition-all">Créer mon profil</a>
        </div>
    @else
        <!-- Profile Header Card -->
        <div class="glass-card rounded-[3rem] p-10 flex flex-col md:flex-row gap-10 items-center md:items-start relative overflow-hidden">
            <div class="relative">
                <div class="w-48 h-48 rounded-[2.5rem] overflow-hidden border-4 border-white/5">
                    @if($profile->photo)
                        <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-accent-blue to-accent-purple flex items-center justify-center text-5xl font-bold">
                            {{ substr($profile->user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-2xl bg-green-500 border-4 border-dark-900 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            <div class="flex-1 space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold">{{ $profile->user->name }}</h1>
                        <p class="text-accent-blue font-medium mt-1">{{ $profile->secteur_activite }} • {{ $profile->localisation }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('operator.profile.edit') }}" class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-xs font-bold uppercase tracking-wider hover:bg-white/10 transition-all">Modifier le Profil</a>
                        <button class="p-3 rounded-2xl bg-accent-blue text-white hover:bg-accent-blue/90 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex gap-4">
                    <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-widest text-gray-400">Communauté vérifiée</span>
                    <span class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-widest text-gray-400">Membre Premium</span>
                </div>

                <p class="text-gray-400 leading-relaxed">
                    {{ $profile->bio ?? "Aucune biographie renseignée pour le moment. Partagez votre parcours avec la communauté !" }}
                </p>
            </div>
            
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-accent-blue/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
        </div>

        <div class="grid grid-cols-3 gap-8">
            <!-- Stats & Info -->
            <div class="space-y-8">
                <div class="glass-card p-8 rounded-[2.5rem] text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">Impact Score</p>
                    <p class="text-5xl font-bold text-white">2,480</p>
                    <p class="text-xs text-gray-500 mt-4 leading-relaxed">Top 5% des membres les plus actifs ce mois-ci.</p>
                </div>

                <div class="glass-card p-8 rounded-[2.5rem]">
                    <h3 class="font-bold mb-6">Badges Récents</h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-accent-blue/20 flex items-center justify-center text-accent-blue" title="Early Adopter">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-accent-purple/20 flex items-center justify-center text-accent-purple" title="Speaker">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-500" title="Helper">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="col-span-2 space-y-8">
                <div class="glass-card p-8 rounded-[2.5rem]">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="font-bold">Activités Récentes</h3>
                        <button class="text-xs text-accent-blue font-bold uppercase tracking-wider">Voir Tout</button>
                    </div>
                    
                    <div class="space-y-8 relative before:absolute before:inset-0 before:left-[19px] before:w-px before:bg-white/5">
                        <div class="relative flex gap-6 items-start">
                            <div class="w-10 h-10 rounded-full bg-accent-blue flex items-center justify-center z-10">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold">Photo de profil mise à jour</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 2 jours • Dans Paramètres</p>
                            </div>
                        </div>

                        <div class="relative flex gap-6 items-start">
                            <div class="w-10 h-10 rounded-full bg-accent-purple flex items-center justify-center z-10">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold">Nouvelle discussion lancée</p>
                                <p class="text-xs text-gray-500 mt-1">"L'avenir de l'IA décentralisée" • Il y a 4 jours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Shortcuts -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="glass-card p-6 rounded-3xl flex items-center gap-4 hover:bg-white/5 transition-all cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Système</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Apparence</p>
                        </div>
                    </div>
                    <div class="glass-card p-6 rounded-3xl flex items-center gap-4 hover:bg-white/5 transition-all cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Confidentialité</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Sécurité</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
