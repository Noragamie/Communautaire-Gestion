@extends('layouts.app')
@section('title', 'Créer mon profil')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Créer mon profil professionnel</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">Complétez votre profil pour rejoindre notre communauté d'entrepreneurs</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-12 flex items-center justify-between">
        <div class="flex items-center w-full">
            <div class="flex flex-col items-center flex-1">
                <div class="w-12 h-12 rounded-full bg-accent-blue text-white flex items-center justify-center font-bold text-lg">1</div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">Informations</p>
            </div>
            <div class="flex-1 h-1 bg-gray-300 dark:bg-gray-600 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 flex items-center justify-center font-bold text-lg">2</div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-2">Documents</p>
            </div>
            <div class="flex-1 h-1 bg-gray-300 dark:bg-gray-600 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 flex items-center justify-center font-bold text-lg">3</div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-2">Confirmation</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('operator.profile.store') }}" enctype="multipart/form-data"
          x-data="{ loading: false, photoPreview: null }" @submit="loading = true" class="space-y-8">
        @csrf

        <!-- Section 1: Informations de base -->
        <div class="glass-card rounded-2xl p-8 border border-white/10">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-accent-blue text-white flex items-center justify-center text-sm font-bold">1</span>
                Informations de base
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Catégorie -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Catégorie professionnelle *</label>
                    <select name="category_id" required
                            class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent transition-all text-gray-900 dark:text-white">
                        <option value="">Sélectionner une catégorie...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Localisation -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Localisation *</label>
                    <input type="text" name="localisation" value="{{ old('localisation') }}" required
                           class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent transition-all text-gray-900 dark:text-white"
                           placeholder="Ex: Cotonou">
                    @error('localisation')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Secteur d'activité -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Secteur d'activité *</label>
                    <input type="text" name="secteur_activite" value="{{ old('secteur_activite') }}" required
                           class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent transition-all text-gray-900 dark:text-white"
                           placeholder="Ex: Artisanat, Commerce, Services">
                    @error('secteur_activite')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Bio -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Biographie</label>
                <textarea name="bio" rows="4"
                          class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent transition-all text-gray-900 dark:text-white"
                          placeholder="Parlez-nous de vous, votre expérience, vos compétences...">{{ old('bio') }}</textarea>
            </div>

            <!-- Téléphone -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}"
                       class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue focus:border-transparent transition-all text-gray-900 dark:text-white"
                       placeholder="+229 XX XX XX XX">
            </div>

            <!-- Photo de profil -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Photo de profil</label>
                <div class="flex items-center gap-6">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-24 h-24 rounded-full object-cover border-4 border-accent-blue shadow-lg">
                    </template>
                    <template x-if="!photoPreview">
                        <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-dark-700 border-4 border-gray-300 dark:border-white/10 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </template>
                    <div class="flex-1">
                        <input type="file" name="photo" accept="image/*"
                               @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-accent-blue transition-all text-gray-900 dark:text-white">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">JPG, PNG ou GIF (Max 2 Mo)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Documents (CV et autres) -->
        <div class="glass-card rounded-2xl p-8 border border-white/10">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-accent-blue text-white flex items-center justify-center text-sm font-bold">2</span>
                Documents professionnels
            </h2>

            <!-- CV (Obligatoire) -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-3">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white">Curriculum Vitae (CV) *</label>
                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 text-xs font-semibold rounded">Obligatoire</span>
                </div>
                <div class="relative border-2 border-dashed border-gray-300 dark:border-white/10 rounded-xl p-8 text-center hover:border-accent-blue transition-colors cursor-pointer group">
                    <input type="file" name="documents[cv]" accept=".pdf,.doc,.docx" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3 group-hover:text-accent-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Cliquez ou déposez votre CV</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, DOC ou DOCX (Max 5 Mo)</p>
                    </div>
                </div>
                @error('documents.cv')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <!-- Autres documents (Optionnel) -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Documents supplémentaires</label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Certificats, diplômes, lettres de recommandation, etc.</p>
                <div class="relative border-2 border-dashed border-gray-300 dark:border-white/10 rounded-xl p-8 text-center hover:border-accent-blue transition-colors cursor-pointer group">
                    <input type="file" name="documents[other][]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3 group-hover:text-accent-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Cliquez ou déposez vos documents</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, DOC, DOCX, JPG ou PNG (Max 5 Mo chacun)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Confirmation -->
        <div class="glass-card rounded-2xl p-8 border border-white/10">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-accent-blue text-white flex items-center justify-center text-sm font-bold">3</span>
                Confirmation
            </h2>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/50 rounded-xl p-4 mb-6">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Veuillez noter :</strong> Votre profil sera soumis pour approbation par notre équipe d'administration. Vous recevrez une notification une fois votre profil validé ou rejeté.
                </p>
            </div>

            <div class="flex items-start gap-3 mb-6">
                <input type="checkbox" id="agree" required class="w-5 h-5 rounded border-gray-300 dark:border-white/10 text-accent-blue focus:ring-accent-blue">
                <label for="agree" class="text-sm text-gray-700 dark:text-gray-300">
                    J'accepte que mes informations soient traitées conformément à notre politique de confidentialité et que mon profil soit visible dans l'annuaire une fois approuvé.
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('home') }}"
                   class="flex-1 px-6 py-4 border border-gray-300 dark:border-white/10 rounded-xl hover:bg-gray-50 dark:hover:bg-dark-700 font-semibold text-gray-900 dark:text-white transition-all text-center">
                    Annuler
                </a>
                <button type="submit"
                        :disabled="loading"
                        class="flex-1 px-6 py-4 rounded-xl bg-accent-blue hover:bg-accent-blue/90 text-white font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span x-show="!loading">Soumettre mon profil</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Soumission en cours...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
