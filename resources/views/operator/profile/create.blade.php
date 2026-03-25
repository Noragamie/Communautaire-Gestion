@extends('layouts.app')
@section('title', 'Inscription - Créer mon profil')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Mon Profile</h1>
        <p class="text-gray-600">Rejoignez la communauté des acteurs économiques de la commune</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8 flex items-center justify-center gap-4" x-data="{ currentStep: 3 }">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium text-gray-700">Informations personnelles</span>
        </div>
        <div class="w-12 h-0.5 bg-gray-300"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-sm font-medium text-gray-700">Informations professionnelles</span>
        </div>
        <div class="w-12 h-0.5 bg-gray-300"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-bold">3</div>
            <span class="text-sm font-medium text-primary-600">Documents</span>
        </div>
        <div class="w-12 h-0.5" :class="currentStep >= 4 ? 'bg-primary-600' : 'bg-gray-300'"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                 :class="currentStep >= 4 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-500'">4</div>
            <span class="text-sm font-medium" :class="currentStep >= 4 ? 'text-primary-600' : 'text-gray-500'">Récapitulatif</span>
        </div>
    </div>

    <form method="POST" action="{{ route('operator.profile.store') }}" enctype="multipart/form-data"
          x-data="{ 
              loading: false, 
              photoPreview: null,
              cvFile: null,
              otherFiles: [],
              currentStep: 3
          }" 
          @submit.prevent="if(currentStep === 4) { loading = true; $el.submit(); }"
          class="space-y-6">
        @csrf

        <!-- Documents Section -->
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Documents</h2>
                <p class="text-sm text-gray-600">Joignez vos documents justificatifs (optionnel - simulation)</p>
            </div>

            <!-- Photo de profil -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Photo de profil</label>
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-24 h-24 rounded-xl object-cover border-2 border-gray-200">
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-24 h-24 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1">
                        <div class="relative">
                            <input type="file" name="photo" accept="image/*" id="photo-input"
                                   @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                                   class="hidden">
                            <label for="photo-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Parcourir...
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Formats acceptés : JPG, PNG, GIF. Taille max : 2Mo</p>
                        @error('photo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- CV / Resume -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-3">
                    <label class="block text-sm font-semibold text-gray-700">CV / Resume</label>
                    <span x-show="!cvFile" class="px-2 py-0.5 bg-red-50 text-red-700 text-xs font-medium rounded">Aucun fichier sélectionné</span>
                    <span x-show="cvFile" class="px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded" x-cloak>Fichier sélectionné</span>
                </div>
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-primary-500 transition-colors group cursor-pointer"
                     @click="$refs.cvInput.click()">
                    <input type="file" name="documents[cv]" accept=".pdf,.doc,.docx" 
                           x-ref="cvInput"
                           @change="cvFile = $event.target.files[0]"
                           class="hidden">
                    <div class="text-center pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary-50 transition-colors">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-1">
                            <span x-show="!cvFile">Parcourir ou glisser-déposer</span>
                            <span x-show="cvFile" x-text="cvFile ? cvFile.name : ''" class="text-primary-600" x-cloak></span>
                        </p>
                        <p class="text-xs text-gray-500">PDF, DOC. Taille max : 5Mo</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Les documents sont optionnels mais aident à valider votre profil plus rapidement. Ils seront examinés par l'administration.</p>
                @error('documents.cv')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Autres documents -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Autres documents</label>
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-primary-500 transition-colors group cursor-pointer"
                     @click="$refs.otherInput.click()">
                    <input type="file" name="documents[other][]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple
                           x-ref="otherInput"
                           @change="otherFiles = Array.from($event.target.files)"
                           class="hidden">
                    <div class="text-center pointer-events-none">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary-50 transition-colors">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-1">
                            <span x-show="otherFiles.length === 0">Parcourir ou glisser-déposer</span>
                            <span x-show="otherFiles.length > 0" x-text="`${otherFiles.length} fichier(s) sélectionné(s)`" class="text-primary-600" x-cloak></span>
                        </p>
                        <p class="text-xs text-gray-500">Certificats, diplômes, lettres de recommandation, etc.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Informations complémentaires</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                    <select name="category_id" required class="input-modern">
                        <option value="">Sélectionner une catégorie...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Localisation *</label>
                    <input type="text" name="localisation" value="{{ old('localisation') }}" required
                           class="input-modern" placeholder="Ex: Cotonou, Porto-Novo">
                    @error('localisation')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Secteur d'activité *</label>
                    <input type="text" name="secteur_activite" value="{{ old('secteur_activite') }}" required
                           class="input-modern" placeholder="Ex: Commerce, Artisanat, Services">
                    @error('secteur_activite')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}"
                           class="input-modern" placeholder="+229 XX XX XX XX">
                    @error('telephone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Biographie</label>
                    <textarea name="bio" rows="4" class="input-modern"
                              placeholder="Présentez-vous en quelques mots...">{{ old('bio') }}</textarea>
                    @error('bio')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Compétences</label>
                    <textarea name="competences" rows="3" class="input-modern"
                              placeholder="Listez vos compétences principales...">{{ old('competences') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Expérience</label>
                    <textarea name="experience" rows="3" class="input-modern"
                              placeholder="Décrivez votre expérience professionnelle...">{{ old('experience') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Niveau d'étude</label>
                    <select name="niveau_etude" class="input-modern">
                        <option value="">Sélectionner...</option>
                        <option value="bac" {{ old('niveau_etude') == 'bac' ? 'selected' : '' }}>Baccalauréat</option>
                        <option value="licence" {{ old('niveau_etude') == 'licence' ? 'selected' : '' }}>Licence</option>
                        <option value="master" {{ old('niveau_etude') == 'master' ? 'selected' : '' }}>Master</option>
                        <option value="doctorat" {{ old('niveau_etude') == 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                        <option value="autre" {{ old('niveau_etude') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Note importante -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">Validation du profil</p>
                    <p>Les documents sont optionnels mais aident à valider votre profil plus rapidement. Ils seront examinés par l'administration.</p>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div x-show="currentStep === 3" class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <!-- Le contenu des documents reste ici -->
        </div>

        <!-- Récapitulatif Section -->
        <div x-show="currentStep === 4" class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Récapitulatif</h2>
                <p class="text-sm text-gray-600">Vérifiez vos informations avant de soumettre</p>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Informations personnelles</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Localisation:</span>
                            <span class="text-gray-900 font-medium ml-2" x-text="document.querySelector('[name=localisation]')?.value || 'Non renseigné'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Téléphone:</span>
                            <span class="text-gray-900 font-medium ml-2" x-text="document.querySelector('[name=telephone]')?.value || 'Non renseigné'"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Informations professionnelles</h3>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="text-gray-500">Secteur d'activité:</span>
                            <span class="text-gray-900 font-medium ml-2" x-text="document.querySelector('[name=secteur_activite]')?.value || 'Non renseigné'"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Documents</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" x-show="cvFile">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500">CV:</span>
                            <span class="text-gray-900 font-medium ml-2" x-text="cvFile ? cvFile.name : 'Non fourni'"></span>
                        </div>
                        <div class="flex items-center gap-2" x-show="photoPreview">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500">Photo de profil fournie</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="button" @click="currentStep = Math.max(3, currentStep - 1)"
                    class="flex-1 text-center px-6 py-3 border border-gray-300 rounded-xl font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Précédent
            </button>
            <button type="button" @click="currentStep = 4" x-show="currentStep === 3"
                    class="flex-1 px-6 py-3 bg-primary-600 text-white rounded-xl font-medium hover:bg-primary-700 transition-colors">
                Suivant
            </button>
            <button type="submit" :disabled="loading" x-show="currentStep === 4"
                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span x-show="!loading">Soumettre mon profil</span>
                <span x-show="loading" class="flex items-center gap-2" x-cloak>
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Envoi...
                </span>
            </button>
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
