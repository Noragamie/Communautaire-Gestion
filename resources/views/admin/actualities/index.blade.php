<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">

<div class="flex min-h-screen" x-data="{ showDeleteModal: false, deleteId: null, deleteTitle: '' }">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-indigo-900 to-purple-900 text-white shadow-2xl">
        <div class="p-6 border-b border-indigo-800">
            <p class="font-bold text-xl bg-gradient-to-r from-white to-indigo-200 bg-clip-text text-transparent">Administration</p>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Tableau de bord
            </a>
            <a href="{{ route('admin.profiles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Profils
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Catégories
            </a>
            <a href="{{ route('admin.actualities.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/20 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Actualités
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Utilisateurs
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="animate-fade-in">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-8">
                Gestion des Actualités
            </h1>

            <!-- Create Form -->
            <div class="glass rounded-2xl shadow-xl p-8 mb-8 border border-white/50" x-data="{ loading: false, imagePreview: null }">
                <h2 class="text-2xl font-bold mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Créer une actualité
                </h2>
                <form method="POST" action="{{ route('admin.actualities.store') }}" enctype="multipart/form-data"
                      @submit="loading = true" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                        <input type="text" name="title" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Titre de l'actualité">
                        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                        <textarea name="content" rows="6" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                  placeholder="Contenu de l'actualité..."></textarea>
                        @error('content')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <div class="flex items-center gap-4">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-32 h-32 rounded-xl object-cover border-2 border-indigo-200">
                            </template>
                            <input type="file" name="image" accept="image/*" 
                                   @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" 
                            :disabled="loading"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium disabled:opacity-50">
                        <span x-show="!loading">Créer l'actualité</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Création...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Actualities List -->
            <div class="space-y-6">
                @foreach($actualities as $actuality)
                    <div class="glass rounded-2xl shadow-xl p-6 border border-white/50 hover:shadow-2xl transition-all">
                        <div class="flex justify-between items-start gap-6">
                            <div class="flex-1">
                                <h3 class="font-bold text-2xl text-gray-900 mb-2">{{ $actuality->title }}</h3>
                                <p class="text-sm text-gray-500 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Par {{ $actuality->author->name }}
                                </p>
                                @if($actuality->is_published)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg>
                                        Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg>
                                        Brouillon
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-3">
                                @if(!$actuality->is_published)
                                    <form method="POST" action="{{ route('admin.actualities.publish', $actuality) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                                            Publier
                                        </button>
                                    </form>
                                @endif
                                <button @click="showDeleteModal = true; deleteId = {{ $actuality->id }}; deleteTitle = '{{ $actuality->title }}'"
                                        class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
         @click.self="showDeleteModal = false">
        <div class="glass rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 border border-white/50 animate-fade-in">
            <h3 class="text-2xl font-bold mb-4 text-gray-900">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">
                Êtes-vous sûr de vouloir supprimer l'actualité <strong x-text="deleteTitle"></strong> ?
            </p>
            <div class="flex gap-4">
                <button @click="showDeleteModal = false" 
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Annuler
                </button>
                <form :action="`{{ route('admin.actualities.index') }}/${deleteId}`" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
                <form method="POST" action="{{ route('admin.actualities.store') }}" enctype="multipart/form-data"
                      @submit="loading = true" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                        <input type="text" name="title" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Titre de l'actualité">
                        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                        <textarea name="content" rows="6" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                  placeholder="Contenu de l'actualité..."></textarea>
                        @error('content')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <div class="flex items-center gap-4">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-32 h-32 rounded-xl object-cover border-2 border-gray-200">
                            </template>
                            <input type="file" name="image" accept="image/*" 
                                   @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" 
                            :disabled="loading"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 font-medium transition-all disabled:opacity-50">
                        <span x-show="!loading">Créer l'actualité</span>
                        <span x-show="loading" x-cloak>Création...</span>
                    </button>
                </form>
            </div>

            <!-- Actualities List -->
            <div class="space-y-6">
                @foreach($actualities as $actuality)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-start gap-6">
                            <div class="flex-1">
                                <h3 class="font-bold text-xl text-gray-900 mb-2">{{ $actuality->title }}</h3>
                                <p class="text-sm text-gray-500 mb-3">Par {{ $actuality->author->name }}</p>
                                @if($actuality->is_published)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Brouillon
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-3">
                                @if(!$actuality->is_published)
                                    <form method="POST" action="{{ route('admin.actualities.publish', $actuality) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 font-medium transition-all">
                                            Publier
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.actualities.destroy', $actuality) }}"
                                      onsubmit="return confirm('Supprimer cette actualité ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 font-medium transition-all">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
