@extends('layouts.app')
@section('title', 'Créer mon profil - CommunePro')

@php
    $initialWizardStep = 1;
    if ($errors->any()) {
        foreach ($errors->keys() as $key) {
            $key = (string) $key;
            if ($key === 'photo' || str_starts_with($key, 'documents.')) {
                $initialWizardStep = 2;
                break;
            }
        }
    }
@endphp

@section('content')
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 rounded-2xl border border-gray-100">
    <div class="max-w-6xl mx-auto"
         x-data="{
             currentStep: {{ $initialWizardStep }},
             loading: false,
             photoPreview: null,
             photoFileName: null,
             cvFile: null,
             otherFiles: [],
             recapTick: 0,
             formEl() {
                 return this.$refs.wizardForm || null;
             },
             nextStep1() {
                 const root = this.$refs.step1;
                 if (!root) return;
                 const firstInvalid = root.querySelector(':invalid');
                 if (firstInvalid) {
                     firstInvalid.focus({ preventScroll: false });
                     firstInvalid.reportValidity();
                     return;
                 }
                 this.currentStep = 2;
             },
             nextStep2() {
                 const root = this.$refs.step2;
                 if (!root) return;
                 const firstInvalid = root.querySelector(':invalid');
                 if (firstInvalid) {
                     firstInvalid.focus({ preventScroll: false });
                     firstInvalid.reportValidity();
                     return;
                 }
                 this.currentStep = 3;
                 this.recapTick++;
             },
             prevStep() {
                 if (this.currentStep > 1) this.currentStep--;
             },
             categoryLabel() {
                 this.recapTick;
                 let s = this.$refs.categorySelect;
                 if (!s) s = this.formEl()?.querySelector('#category_id');
                 if (!s || s.selectedIndex < 0) return '—';
                 const t = s.options[s.selectedIndex]?.text?.trim();
                 return (s.value && t) ? t : '—';
             },
             niveauLabel() {
                 this.recapTick;
                 let s = this.$refs.niveauSelect;
                 if (!s) s = this.formEl()?.querySelector('#niveau_etude');
                 if (!s || s.selectedIndex < 0) return 'Non renseigné';
                 const t = s.options[s.selectedIndex]?.text?.trim();
                 return s.value && t ? t : 'Non renseigné';
             },
             fieldVal(name) {
                 this.recapTick;
                 const form = this.formEl();
                 if (!form) return '';
                 const el = form.querySelector(`[name='${name}']`);
                 if (!el || !('value' in el)) return '';
                 return String(el.value || '').trim();
             },
             contactVisibleLabel() {
                 this.recapTick;
                 const el = this.formEl()?.querySelector('#contact_visible_create');
                 return el && el.checked ? 'Oui' : 'Non';
             }
         }">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Créer mon profil</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base max-w-2xl">
                    Complétez votre fiche en trois étapes : vos informations, vos documents puis un récapitulatif avant envoi pour validation.
                </p>
            </div>
        </div>

        {{-- Indicateur d’étapes (3) --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:flex-wrap sm:items-center sm:justify-center gap-4 sm:gap-0">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold transition-colors"
                     :class="currentStep > 1 ? 'bg-green-500 text-white' : (currentStep === 1 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-500')"
                     x-text="currentStep > 1 ? '✓' : '1'"></div>
                <span class="text-sm font-medium" :class="currentStep === 1 ? 'text-primary-700' : 'text-gray-600'">Informations</span>
            </div>
            <div class="hidden sm:block w-8 lg:w-14 h-0.5 shrink-0 mx-2 rounded-full transition-colors" :class="currentStep > 1 ? 'bg-primary-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold transition-colors"
                     :class="currentStep > 2 ? 'bg-green-500 text-white' : (currentStep === 2 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-500')"
                     x-text="currentStep > 2 ? '✓' : '2'"></div>
                <span class="text-sm font-medium" :class="currentStep === 2 ? 'text-primary-700' : 'text-gray-600'">Documents</span>
            </div>
            <div class="hidden sm:block w-8 lg:w-14 h-0.5 shrink-0 mx-2 rounded-full transition-colors" :class="currentStep > 2 ? 'bg-primary-300' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold transition-colors"
                     :class="currentStep >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-500'">3</div>
                <span class="text-sm font-medium" :class="currentStep === 3 ? 'text-primary-700' : 'text-gray-600'">Récapitulatif</span>
            </div>
        </div>

        <form id="profile-create-form" x-ref="wizardForm" method="POST" action="{{ route('operator.profile.store') }}" enctype="multipart/form-data" novalidate
              class="space-y-6"
              @submit="loading = true">
            @csrf

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <p class="font-semibold mb-2">Merci de corriger les points suivants :</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Étape 1 : Infos --}}
            <div x-show="currentStep === 1"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6"
                 x-ref="step1">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-1 pb-2 border-b border-gray-100">Informations professionnelles</h2>
                    <p class="text-sm text-gray-600 mb-6">Ces champs structurent votre fiche dans l’annuaire.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">Catégorie <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" required x-ref="categorySelect"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                                <option value="">Sélectionner une catégorie…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm">
                            <span class="font-semibold text-gray-900">Commune</span>
                            <p class="text-gray-800 mt-0.5">{{ Auth::user()->commune?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Renseignée à l’inscription ; elle sert de localisation dans l’annuaire.</p>
                        </div>
                        <div>
                            <label for="secteur_activite" class="block text-sm font-semibold text-gray-900 mb-2">Secteur d’activité <span class="text-red-500">*</span></label>
                            <input type="text" id="secteur_activite" name="secteur_activite" value="{{ old('secteur_activite') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                                   placeholder="Ex. Commerce, services, artisanat…">
                            @error('secteur_activite')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="niveau_etude" class="block text-sm font-semibold text-gray-900 mb-2">Niveau d’études</label>
                            <select id="niveau_etude" name="niveau_etude" x-ref="niveauSelect"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                                <option value="">Non renseigné</option>
                                <option value="bac" @selected(old('niveau_etude') === 'bac')>Baccalauréat</option>
                                <option value="licence" @selected(old('niveau_etude') === 'licence')>Licence</option>
                                <option value="master" @selected(old('niveau_etude') === 'master')>Master</option>
                                <option value="doctorat" @selected(old('niveau_etude') === 'doctorat')>Doctorat</option>
                                <option value="autre" @selected(old('niveau_etude') === 'autre')>Autre</option>
                            </select>
                            @error('niveau_etude')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-1 pb-2 border-b border-gray-100">Présentation &amp; parcours</h2>
                    <p class="text-sm text-gray-600 mb-6">Optionnel mais recommandé pour votre visibilité.</p>
                    <div class="space-y-6">
                        <div>
                            <label for="bio" class="block text-sm font-semibold text-gray-900 mb-2">Présentation</label>
                            <textarea id="bio" name="bio" rows="4" maxlength="255"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                                      placeholder="Quelques lignes sur vous…">{{ old('bio') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">255 caractères maximum</p>
                            @error('bio')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="competences" class="block text-sm font-semibold text-gray-900 mb-2">Compétences</label>
                            <p class="text-xs text-gray-500 mb-2">Séparez les compétences par des virgules ou des retours à la ligne.</p>
                            <textarea id="competences" name="competences" rows="3"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                                      placeholder="Ex. Gestion de projet, comptabilité…">{{ old('competences') }}</textarea>
                            @error('competences')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="experience" class="block text-sm font-semibold text-gray-900 mb-2">Expérience professionnelle</label>
                            <textarea id="experience" name="experience" rows="4"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                                      placeholder="Votre parcours, missions récentes…">{{ old('experience') }}</textarea>
                            @error('experience')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-1 pb-2 border-b border-gray-100">Contact &amp; visibilité</h2>
                    <p class="text-sm text-gray-600 mb-6">Ces informations peuvent apparaître sur votre fiche publique selon votre choix.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone" class="block text-sm font-semibold text-gray-900 mb-2">Téléphone</label>
                            <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                                   placeholder="+229 …">
                            @error('telephone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="site_web" class="block text-sm font-semibold text-gray-900 mb-2">Site web</label>
                            <input type="text" id="site_web" name="site_web" value="{{ old('site_web') }}" placeholder="https://"
                                   inputmode="url" autocomplete="url"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                            @error('site_web')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Coordonnées sur la fiche publique</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Téléphone et site web visibles dans l’annuaire si activé.</p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <input type="hidden" name="contact_visible" value="0">
                                    <input type="checkbox" name="contact_visible" value="1" id="contact_visible_create" class="peer sr-only"
                                           @checked((string) old('contact_visible', '1') === '1')>
                                    <label for="contact_visible_create"
                                           class="relative block h-6 w-11 cursor-pointer rounded-full bg-gray-300 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary-600 peer-checked:after:translate-x-5"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Étape 2 : Documents --}}
            <div x-show="currentStep === 2"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm" x-ref="step2">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Documents</h2>
                    <p class="text-sm text-gray-600 mb-6">Le <strong class="text-gray-800">CV est obligatoire</strong> pour la première soumission. Photo et autres pièces sont facultatifs.</p>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Photo de profil <span class="text-gray-500 font-normal">(optionnel)</span></label>
                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            <div class="shrink-0">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" alt="" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border border-gray-200 shadow-sm">
                                </template>
                                <template x-if="!photoPreview">
                                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                </template>
                            </div>
                            <div class="min-w-0 flex-1 w-full">
                                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/jpg" id="photo-input"
                                       @change="
                                           if (photoPreview) URL.revokeObjectURL(photoPreview);
                                           photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null;
                                           photoFileName = $event.target.files[0]?.name || null;
                                       "
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG ou WebP — max. 2 Mo</p>
                                @error('photo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">CV <span class="text-red-500">*</span></label>
                        <div class="relative cursor-pointer rounded-xl border-2 border-dashed border-gray-300 p-6 transition-colors hover:border-primary-500 group"
                             @click="$refs.cvInput.click()">
                            <input type="file" name="documents[cv]" accept=".pdf,.doc,.docx,application/pdf" required
                                   x-ref="cvInput"
                                   @change="cvFile = $event.target.files[0] || null"
                                   class="sr-only">
                            <div class="text-center pointer-events-none">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 group-hover:bg-primary-50 transition-colors">
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    <span x-show="!cvFile">Cliquez pour choisir un fichier (PDF, DOC, DOCX — max. 5 Mo)</span>
                                    <span x-show="cvFile" x-text="cvFile ? cvFile.name : ''" class="text-primary-700" x-cloak></span>
                                </p>
                            </div>
                        </div>
                        @error('documents.cv')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Autres documents <span class="text-gray-500 font-normal">(optionnel)</span></label>
                        <div class="relative cursor-pointer rounded-xl border-2 border-dashed border-gray-300 p-6 transition-colors hover:border-primary-500 group"
                             @click="$refs.otherInput.click()">
                            <input type="file" name="documents[other][]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple
                                   x-ref="otherInput"
                                   @change="otherFiles = Array.from($event.target.files || [])"
                                   class="sr-only">
                            <div class="text-center pointer-events-none">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 group-hover:bg-primary-50 transition-colors">
                                    <svg class="h-6 w-6 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    <span x-show="otherFiles.length === 0">Certificats, diplômes, etc.</span>
                                    <span x-show="otherFiles.length > 0" x-text="`${otherFiles.length} fichier(s) sélectionné(s)`" class="text-primary-700" x-cloak></span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG — max. 5 Mo par fichier</p>
                            </div>
                        </div>
                        @foreach ($errors->getMessages() as $errKey => $errMessages)
                            @if (str_starts_with((string) $errKey, 'documents.other'))
                                @foreach ($errMessages as $message)
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 flex gap-3">
                    <svg class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <p class="text-sm text-blue-900">Après envoi, votre profil sera examiné par l’administration. Vous recevrez une notification lorsque la décision sera prise.</p>
                </div>
            </div>

            {{-- Étape 3 : Récap --}}
            <div x-show="currentStep === 3"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Récapitulatif</h2>
                <p class="text-sm text-gray-600 mb-6">Vérifiez vos informations avant de soumettre votre profil.</p>

                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Profil</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="sm:col-span-2"><dt class="text-gray-500">Catégorie</dt><dd class="font-medium text-gray-900 mt-0.5" x-text="categoryLabel()"></dd></div>
                            <div><dt class="text-gray-500">Commune</dt><dd class="font-medium text-gray-900 mt-0.5">{{ Auth::user()->commune?->name ?? '—' }}</dd></div>
                            <div><dt class="text-gray-500">Secteur d’activité</dt><dd class="font-medium text-gray-900 mt-0.5" x-text="fieldVal('secteur_activite') || '—'"></dd></div>
                            <div class="sm:col-span-2"><dt class="text-gray-500">Niveau d’études</dt><dd class="font-medium text-gray-900 mt-0.5" x-text="niveauLabel()"></dd></div>
                            <div class="sm:col-span-2"><dt class="text-gray-500">Présentation</dt><dd class="font-medium text-gray-900 mt-0.5 whitespace-pre-line" x-text="fieldVal('bio') || '—'"></dd></div>
                            <div class="sm:col-span-2"><dt class="text-gray-500">Compétences</dt><dd class="font-medium text-gray-900 mt-0.5 whitespace-pre-line" x-text="fieldVal('competences') || '—'"></dd></div>
                            <div class="sm:col-span-2"><dt class="text-gray-500">Expérience</dt><dd class="font-medium text-gray-900 mt-0.5 whitespace-pre-line" x-text="fieldVal('experience') || '—'"></dd></div>
                        </dl>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 mb-3">Contact</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div><dt class="text-gray-500">Téléphone</dt><dd class="font-medium text-gray-900 mt-0.5" x-text="fieldVal('telephone') || '—'"></dd></div>
                            <div><dt class="text-gray-500">Site web</dt><dd class="font-medium text-gray-900 mt-0.5 break-all" x-text="fieldVal('site_web') || '—'"></dd></div>
                            <div class="sm:col-span-2"><dt class="text-gray-500">Coordonnées visibles sur la fiche publique</dt><dd class="font-medium text-gray-900 mt-0.5" x-text="contactVisibleLabel()"></dd></div>
                        </dl>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 mb-3">Documents</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex flex-wrap gap-2"><span class="text-gray-500">Photo</span><span class="font-medium text-gray-900" x-text="photoFileName || 'Aucune'"></span></li>
                            <li class="flex flex-wrap gap-2"><span class="text-gray-500">CV</span><span class="font-medium text-gray-900" x-text="cvFile ? cvFile.name : '—'"></span></li>
                            <li x-show="otherFiles.length > 0" x-cloak>
                                <span class="text-gray-500 block mb-1">Autres</span>
                                <ul class="list-disc list-inside font-medium text-gray-900">
                                    <template x-for="(f, i) in otherFiles" :key="i">
                                        <li x-text="f.name"></li>
                                    </template>
                                </ul>
                            </li>
                            <li x-show="otherFiles.length === 0" class="text-gray-500">Aucun autre document</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if($errors->has('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first('error') }}</div>
            @endif

            <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-between">
                <button type="button" @click="prevStep()"
                        x-show="currentStep > 1"
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-gray-300 font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    Précédent
                </button>
                <div class="flex flex-col sm:flex-row gap-3 sm:ml-auto">
                    <button type="button" @click="nextStep1()" x-show="currentStep === 1"
                            class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors shadow-sm">
                        Suivant — Documents
                    </button>
                    <button type="button" @click="nextStep2()" x-show="currentStep === 2"
                            class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors shadow-sm">
                        Suivant — Récapitulatif
                    </button>
                    <button type="submit" :disabled="loading" x-show="currentStep === 3"
                            class="inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Soumettre mon profil</span>
                        <span x-show="loading" x-cloak class="flex items-center gap-2">
                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Envoi…
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
