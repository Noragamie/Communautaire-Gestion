@extends('layouts.app')
@section('title', 'Modifier mon profil')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in" x-data="{ loading: false, photoPreview: '{{ $profile->photo ? asset('storage/'.$profile->photo) : null }}' }">
    <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-8">
        Modifier mon profil
    </h1>

    <form method="POST" action="{{ route('operator.profile.update') }}" enctype="multipart/form-data" 
          @submit="loading = true" class="glass rounded-2xl shadow-xl p-8 border border-white/50 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
            <select name="category_id" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $profile->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Localisation *</label>
                <input type="text" name="localisation" value="{{ old('localisation', $profile->localisation) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secteur d'activité *</label>
                <input type="text" name="secteur_activite" value="{{ old('secteur_activite', $profile->secteur_activite) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
            <textarea name="bio" rows="4" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">{{ old('bio', $profile->bio) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
            <input type="text" name="telephone" value="{{ old('telephone', $profile->telephone) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
            <div class="flex items-center gap-4">
                <template x-if="photoPreview">
                    <img :src="photoPreview" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-200">
                </template>
                <input type="file" name="photo" accept="image/*" 
                       @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : photoPreview"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>
        </div>

        <button type="submit" 
                :disabled="loading"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium text-lg disabled:opacity-50">
            <span x-show="!loading">Mettre à jour mon profil</span>
            <span x-show="loading" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Mise à jour...
            </span>
        </button>
    </form>
</div>
@endsection
