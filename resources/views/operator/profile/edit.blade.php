@extends('layouts.app')
@section('title', 'Modifier mon profil')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Modifier mon profil professionnel</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">Mettez à jour vos informations et vos documents</p>
    </div>

    @if($profile->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
            <div class="flex gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-red-800 mb-2">Votre profil a été rejeté</h3>
                    <p class="text-red-700 text-sm mb-3">{{ $profile->motif_rejet }}</p>
                    <p class="text-sm text-red-600">Veuillez corriger les points mentionnés ci-dessus et resoummettre votre profil.</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('operator.profile.update') }}" enctype="multipart/form-data"
          x-data="{ loading: false, photoPreview: '{{ $profile->photo ? asset('storage/'.$profile->photo) : null }}' }" @submit="loading = true" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Section 1: Informations de base -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-bold">1</span>
                Informations de base
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Catégorie -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Catégorie professionnelle *</label>
                    <select name="category_id" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all text-gray-900">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $profile->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Localisation -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Localisation *</label>
                    <input type="text" name="localisation" value="{{ old('localisation', $profile->localisation) }}" required
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all text-gray-900">
                </div>

                <!-- Secteur d'activité -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Secteur d'activité *</label>
                    <input type="text" name="secteur_activite" value="{{ old('secteur_activite', $profile->secteur_activite) }}" required
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all text-gray-900">
                </div>
            </div>

            <!-- Bio -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">Biographie</label>
                <textarea name="bio" rows="4"
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all text-gray-900">{{ old('bio', $profile->bio) }}</textarea>
            </div>

            <!-- Téléphone -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $profile->telephone) }}"
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all text-gray-900">
            </div>

            <!-- Photo de profil -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-900 mb-3">Photo de profil</label>
                <div class="flex items-center gap-6">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-24 h-24 rounded-full object-cover border-4 border-primary-600 shadow-lg">
                    </template>
                    <template x-if="!photoPreview">
                        <div class="w-24 h-24 rounded-full bg-gray-200 border-4 border-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </template>
                    <div class="flex-1">
                        <input type="file" name="photo" accept="image/*"
                               @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : photoPreview"
                               class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all text-gray-900">
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG ou GIF (Max 2 Mo)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Documents -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center text-sm font-bold">2</span>
                Documents professionnels
            </h2>

            <!-- Documents actuels -->
            @if($profile->documents->count() > 0)
                <div class="mb-8">
                    <h3 class="font-semibold text-gray-900 mb-4">Vos documents actuels</h3>
                    <div class="space-y-3">
                        @foreach($profile->documents as $doc)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    @if(in_array(pathinfo($doc->path, PATHINFO_EXTENSION), ['pdf']))
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $doc->original_name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($doc->type) }} • {{ number_format($doc->size / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/'.$doc->path) }}" target="_blank" download
                                   class="ml-4 px-3 py-2 text-accent-blue hover:text-accent-blue/80 font-medium text-sm transition-colors flex-shrink-0">
                                    Télécharger
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-600 mt-4 p-4 bg-blue-50 rounded-lg">
                        <strong>Note :</strong> Vous pouvez mettre à jour vos documents en les réuploadant ci-dessous. Les anciens documents seront remplacés.
                    </p>
                </div>
            @endif

            <!-- CV (Obligatoire) -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-3">
                    <label class="block text-sm font-semibold text-gray-900">Curriculum Vitae (CV)</label>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">Optionnel</span>
                </div>
                <p class="text-xs text-gray-600 mb-3">Laissez vide pour conserver votre CV actuel</p>
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-500 transition-colors cursor-pointer group">
                    <input type="file" name="documents[cv]" accept=".pdf,.doc,.docx"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-900">Cliquez ou déposez votre CV</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC ou DOCX (Max 5 Mo)</p>
                    </div>
                </div>
            </div>

            <!-- Autres documents (Optionnel) -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-3">Documents supplémentaires</label>
                <p class="text-xs text-gray-600 mb-3">Certificats, diplômes, lettres de recommandation, etc.</p>
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-500 transition-colors cursor-pointer group">
                    <input type="file" name="documents[other][]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-900">Cliquez ou déposez vos documents</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG ou PNG (Max 5 Mo chacun)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('operator.profile.show') }}"
               class="flex-1 px-6 py-4 border border-gray-300 rounded-xl hover:bg-gray-50 font-semibold text-gray-900 transition-all text-center">
                Annuler
            </a>
            <button type="submit"
                    :disabled="loading"
                    class="flex-1 px-6 py-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span x-show="!loading">Mettre à jour mon profil</span>
                <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mise à jour...
                </span>
            </button>
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
