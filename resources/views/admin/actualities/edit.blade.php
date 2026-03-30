@extends('layouts.admin')

@section('title', 'Modifier — ' . $actuality->title)

@section('content')
    <form method="POST" action="{{ route('admin.actualities.update', $actuality) }}" enctype="multipart/form-data"
          x-data="{ loading: false, imagePreview: null }" @submit="loading = true">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" x-ref="action" value="draft">

        {{-- En-tête avec boutons --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.actualities.index') }}"
                   class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Modifier l'actualité</h1>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        @click="$refs.action.value = 'draft'"
                        :disabled="loading"
                        class="px-5 py-2.5 border border-gray-300 bg-white text-gray-700 rounded-xl hover:bg-gray-50 font-medium text-sm transition-all disabled:opacity-50">
                    Enregistrer comme brouillon
                </button>
                <button type="submit"
                        @click="$refs.action.value = 'publish'"
                        :disabled="loading"
                        class="px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-medium text-sm transition-all disabled:opacity-50 flex items-center gap-2">
                    <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span>Publier</span>
                </button>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" value="{{ old('title', $actuality->title) }}" required autofocus
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all
                              @error('title') border-red-400 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                <textarea name="content" id="actuality-content" data-easymde rows="10" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all
                                 @error('content') border-red-400 @enderror"
                          placeholder="Contenu en Markdown">{{ old('content', $actuality->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">Le texte est enregistré en Markdown.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                @if($actuality->image && !old('title'))
                    <div class="mb-3">
                        <img src="{{ image_url($actuality, 'image', 'image_data') }}"
                             alt="Image actuelle"
                             class="w-full h-48 object-cover rounded-xl border border-gray-200">
                        <p class="text-xs text-gray-400 mt-1">Image actuelle — remplacer en sélectionnant un nouveau fichier</p>
                    </div>
                @endif
                <div x-show="imagePreview" class="mb-3">
                    <img :src="imagePreview" class="w-full h-48 object-cover rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-400 mt-1">Nouvel aperçu</p>
                </div>
                <input type="file" name="image" accept="image/*"
                       @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF — max 2 Mo. Laisser vide pour conserver l'image actuelle.</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

    </form>
@endsection

@push('scripts')
    @vite(['resources/js/admin-markdown.js'])
@endpush
