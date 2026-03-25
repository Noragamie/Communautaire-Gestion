@extends('layouts.app')

@section('title', 'Mon Profil - Opérateur')

@section('content')
<div class="space-y-8">
    @if(!$profile)
        <!-- Empty State -->
        <div class="card-glass rounded-2xl p-16 shadow-soft text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Créez votre profil</h2>
            <p class="text-gray-600 mb-8">Commencez par créer votre profil pour rejoindre la communauté et partager votre expertise.</p>
            <a href="{{ route('operator.profile.create') }}" class="btn-primary text-white px-8 py-3 rounded-lg font-bold hover:shadow-medium transition-all inline-block">
                Créer mon profil
            </a>
        </div>
    @else
        <!-- Progress Steps -->
        <div class="card-glass rounded-2xl p-8 shadow-soft">
            <div class="flex items-center justify-between">
                <!-- Step 1: Personal Info -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-900">Infos Personnelles</p>
                </div>

                <!-- Connector 1 -->
                <div class="flex-1 h-1 bg-green-500 mx-2 mb-8"></div>

                <!-- Step 2: Professional Info -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-900">Infos Professionnelles</p>
                </div>

                <!-- Connector 2 -->
                <div class="flex-1 h-1 bg-green-500 mx-2 mb-8"></div>

                <!-- Step 3: Documents -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-900">Documents</p>
                </div>

                <!-- Connector 3 -->
                <div class="flex-1 h-1 bg-blue-300 mx-2 mb-8"></div>

                <!-- Step 4: Review -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold mb-2">4</div>
                    <p class="text-sm font-bold text-gray-900">Récapitulatif</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card-glass rounded-2xl p-8 shadow-soft">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Récapitulatif</h2>
            <p class="text-gray-600 mb-8">Vérifiez vos informations avant de soumettre</p>

            <!-- Personal Info Section -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Informations personnelles</h3>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Nom complet</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Email</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Téléphone</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Localisation</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->localisation ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-blue-100 my-8">

            <!-- Professional Info Section -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Informations professionnelles</h3>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Catégorie</p>
                        <p class="text-lg font-semibold text-blue-600">{{ $profile->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Secteur d'activité</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->secteur_activite }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Métier</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->metier ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">Niveau d'étude</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $profile->niveau_etude ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-blue-100 my-8">

            <!-- Documents Section -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Documents</h3>
                <p class="text-gray-600 text-sm">Aucune photo jointe / Aucun CV joint</p>
            </div>

            <!-- Status Alert -->
            @if($profile->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-8 flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-yellow-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">En attente d'approbation</p>
                        <p class="text-sm text-gray-600 mt-1">Votre profil est actuellement en cours de vérification par nos modérateurs.</p>
                    </div>
                </div>
            @elseif($profile->status === 'approved')
                <div class="bg-green-50 border border-green-300 rounded-lg p-4 mb-8 flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">Profil approuvé</p>
                        <p class="text-sm text-gray-600 mt-1">Votre profil est maintenant visible pour la communauté.</p>
                    </div>
                </div>
            @elseif($profile->status === 'rejected')
                <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-8 flex items-start gap-3">
                    <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">Profil rejeté</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $profile->motif_rejet }}</p>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-8 border-t border-blue-100">
                <button class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-all">
                    ← Précédent
                </button>
                <a href="{{ route('operator.profile.edit') }}" class="btn-primary text-white px-8 py-3 rounded-lg font-bold hover:shadow-medium transition-all">
                    Modifier mon profil
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
