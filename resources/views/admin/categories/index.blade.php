@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')
    <div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Gestion des catégories</h1>

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
@endsection
