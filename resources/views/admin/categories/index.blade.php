<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen" x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <p class="font-bold text-xl text-gray-900">Administration</p>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Tableau de bord
            </a>
            <a href="{{ route('admin.profiles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Profils
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Catégories
            </a>
            <a href="{{ route('admin.actualities.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Actualités
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Utilisateurs
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestion des catégories</h1>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl px-6 py-4 mb-6">
                <div class="flex items-center gap-3 text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Add Category Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-8" x-data="{ loading: false }">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Ajouter une catégorie</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}" 
                  @submit="loading = true" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Ex: Artisans">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" name="description" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Description de la catégorie">
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" 
                        :disabled="loading"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 font-medium transition-all disabled:opacity-50">
                    <span x-show="!loading">Ajouter</span>
                    <span x-show="loading" x-cloak>Ajout...</span>
                </button>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Description</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Profils</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($categories as $cat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $cat->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $cat->description }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $cat->profiles_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="showDeleteModal = true; deleteId = {{ $cat->id }}; deleteName = '{{ $cat->name }}'"
                                        class="text-red-600 hover:text-red-700 font-medium text-sm">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
         @click.self="showDeleteModal = false">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-4 text-gray-900">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">
                Supprimer la catégorie <strong x-text="deleteName"></strong> ?
            </p>
            <div class="flex gap-4">
                <button @click="showDeleteModal = false" 
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Annuler
                </button>
                <form :action="`{{ route('admin.categories.index') }}/${deleteId}`" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-medium">
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
