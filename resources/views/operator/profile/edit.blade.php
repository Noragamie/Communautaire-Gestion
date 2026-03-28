@extends('layouts.app')
@section('title', 'Modifier mon profil - CommunePro')

@section('content')
@php
    $docLabels = ['cv' => 'CV', 'autre' => 'Autre document', 'photo' => 'Photo', 'legal' => 'Document légal'];
@endphp
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 rounded-2xl border border-gray-100">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('operator.profile.show') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Retour au profil
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Modifier mon profil</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Mettez à jour vos informations. Les documents ne sont pas obligatoires : laissez les champs vides pour conserver les fichiers actuels.</p>
            </div>
        </div>

        @if($profile->isApproved())
            <div class="mb-6 rounded-xl border border-primary-100 bg-primary-50 p-4 flex gap-3">
                <svg class="w-6 h-6 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-primary-900"><span class="font-semibold">Profil validé.</span> Vos changements seront examinés avant d’être appliqués ; votre fiche publique reste visible en l’état pendant l’examen.</p>
            </div>
        @endif

        @if($profile->status === 'rejected')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5 flex gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <h2 class="font-semibold text-red-900 mb-1">Profil refusé</h2>
                    @if($profile->motif_rejet)
                        <p class="text-sm text-red-800">{{ $profile->motif_rejet }}</p>
                    @endif
                    <p class="text-sm text-red-700 mt-2">Corrigez les points indiqués puis enregistrez pour une nouvelle validation.</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('operator.profile.update') }}" enctype="multipart/form-data"
              x-data="{ loading: false, photoPreview: @js($profile->photo ? asset('storage/'.$profile->photo) : null) }"
              @submit="loading = true"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Identité & catégorie --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Identité &amp; catégorie</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">Catégorie professionnelle <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $profile->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="localisation" class="block text-sm font-semibold text-gray-900 mb-2">Localisation <span class="text-red-500">*</span></label>
                        <input type="text" id="localisation" name="localisation" value="{{ old('localisation', $profile->localisation) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                        @error('localisation')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="secteur_activite" class="block text-sm font-semibold text-gray-900 mb-2">Secteur d’activité <span class="text-red-500">*</span></label>
                        <input type="text" id="secteur_activite" name="secteur_activite" value="{{ old('secteur_activite', $profile->secteur_activite) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                        @error('secteur_activite')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="niveau_etude" class="block text-sm font-semibold text-gray-900 mb-2">Niveau d’études</label>
                        <select id="niveau_etude" name="niveau_etude"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                            <option value="">Non renseigné</option>
                            <option value="bac" @selected(old('niveau_etude', $profile->niveau_etude) === 'bac')>Baccalauréat</option>
                            <option value="licence" @selected(old('niveau_etude', $profile->niveau_etude) === 'licence')>Licence</option>
                            <option value="master" @selected(old('niveau_etude', $profile->niveau_etude) === 'master')>Master</option>
                            <option value="doctorat" @selected(old('niveau_etude', $profile->niveau_etude) === 'doctorat')>Doctorat</option>
                            <option value="autre" @selected(old('niveau_etude', $profile->niveau_etude) === 'autre')>Autre</option>
                        </select>
                        @error('niveau_etude')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Présentation & parcours --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Présentation &amp; parcours</h2>
                <div class="space-y-6">
                    <div>
                        <label for="bio" class="block text-sm font-semibold text-gray-900 mb-2">Présentation</label>
                        <textarea id="bio" name="bio" rows="5"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="competences" class="block text-sm font-semibold text-gray-900 mb-2">Compétences</label>
                        <p class="text-xs text-gray-500 mb-2">Séparez les compétences par des virgules ou des retours à la ligne.</p>
                        <textarea id="competences" name="competences" rows="4"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">{{ old('competences', $profile->competences) }}</textarea>
                        @error('competences')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="experience" class="block text-sm font-semibold text-gray-900 mb-2">Expérience professionnelle</label>
                        <textarea id="experience" name="experience" rows="5"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">{{ old('experience', $profile->experience) }}</textarea>
                        @error('experience')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Contact & visibilité --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Contact &amp; visibilité</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="telephone" class="block text-sm font-semibold text-gray-900 mb-2">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $profile->telephone) }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                        @error('telephone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="site_web" class="block text-sm font-semibold text-gray-900 mb-2">Site web</label>
                        <input type="url" id="site_web" name="site_web" value="{{ old('site_web', $profile->site_web) }}" placeholder="https://"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900">
                        @error('site_web')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Coordonnées sur la fiche publique</p>
                                <p class="text-xs text-gray-500 mt-0.5">Email et téléphone visibles selon ce réglage.</p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <input type="hidden" name="contact_visible" value="0">
                                <input type="checkbox" name="contact_visible" value="1" id="contact_visible_edit" class="peer sr-only"
                                       @checked((string) old('contact_visible', $profile->contact_visible ? '1' : '0') === '1')>
                                <label for="contact_visible_edit"
                                       class="relative block h-6 w-11 cursor-pointer rounded-full bg-gray-300 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary-600 peer-checked:after:translate-x-5"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Photo --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-2 border-b border-gray-100">Photo de profil</h2>
                <p class="text-sm text-gray-600 mb-4">Optionnel — ne choisissez pas de fichier pour conserver la photo actuelle.</p>
                <div class="flex flex-col sm:flex-row items-start gap-6">
                    <div class="flex-shrink-0">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" alt="" class="w-28 h-28 rounded-2xl object-cover border border-gray-200 shadow-sm">
                        </template>
                        <template x-if="!photoPreview">
                            <div class="w-28 h-28 rounded-2xl bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-3xl font-bold">
                                {{ mb_substr($profile->user->name, 0, 1) }}
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 w-full min-w-0">
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/jpg"
                               @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : photoPreview"
                               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG ou WebP — max. 2 Mo</p>
                        @error('photo')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Documents (facultatif) --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-2 pb-2 border-b border-gray-100">Documents</h2>
                <p class="text-sm text-gray-600 mb-6">L’envoi d’un nouveau fichier remplace le CV existant ; les autres pièces s’ajoutent à votre dossier. Laissez vide pour ne rien changer.</p>

                @if($profile->documents->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Fichiers actuels</h3>
                        <ul class="space-y-2">
                            @foreach($profile->documents as $doc)
                                <li class="flex flex-wrap items-center justify-between gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $doc->original_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $docLabels[$doc->type] ?? ucfirst($doc->type) }}@if($doc->size) · {{ number_format($doc->size / 1024, 1) }} Ko @endif</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ asset('storage/'.$doc->path) }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Ouvrir</a>
                                        <form method="POST" action="{{ route('operator.document.destroy', $doc) }}" onsubmit="return confirm('Supprimer ce document ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Supprimer</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Nouveau CV</label>
                        <p class="text-xs text-gray-500 mb-3">Remplace l’ancien CV s’il existe.</p>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 transition-colors">
                            <input type="file" name="documents[cv]" accept=".pdf,.doc,.docx"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-sm font-medium text-gray-900">Cliquez pour choisir un fichier</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC ou DOCX — max. 5 Mo</p>
                        </div>
                        @error('documents.cv')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Autres documents</label>
                        <p class="text-xs text-gray-500 mb-3">Ajoutés à votre dossier (certificats, diplômes, etc.).</p>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 transition-colors">
                            <input type="file" name="documents[other][]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-sm font-medium text-gray-900">Un ou plusieurs fichiers</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, images — max. 5 Mo chacun</p>
                        </div>
                        @error('documents.other.*')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                <a href="{{ route('operator.profile.show') }}"
                   class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-gray-300 bg-white text-gray-800 font-semibold hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" :disabled="loading"
                        class="inline-flex justify-center items-center px-8 py-3 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed gap-2">
                    <span x-show="!loading">Enregistrer les modifications</span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Envoi…
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>[x-cloak]{display:none!important}</style>
@endsection
