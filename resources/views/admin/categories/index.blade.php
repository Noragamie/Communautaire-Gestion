@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')
    <div x-data="{
        showDeleteModal: false,
        deleteId: null,
        deleteName: '',
        profilesCount: 0,
        openDelete(id, name, count) {
            this.deleteId = id;
            this.deleteName = name;
            this.profilesCount = count;
            this.showDeleteModal = true;
        }
    }">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestion des catégories</h1>

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
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Ex: Artisans">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" name="description"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                               placeholder="Description de la catégorie">
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit"
                        :disabled="loading"
                        class="bg-primary-600 text-white px-8 py-3 rounded-xl hover:bg-primary-700 font-semibold transition-all disabled:opacity-50 shadow-lg hover:shadow-xl">
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
                            <td class="px-6 py-4 text-gray-600">{{ $cat->description ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $cat->profiles_count > 0 ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $cat->profiles_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="openDelete({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->profiles_count }})"
                                        class="text-red-600 hover:text-red-700 font-medium text-sm transition-colors">
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

                {{-- Cas : catégorie sans profils --}}
                <template x-if="profilesCount === 0">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Supprimer la catégorie</h3>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Voulez-vous supprimer la catégorie <strong x-text="deleteName"></strong> ?
                            Cette action est irréversible.
                        </p>
                        <div class="flex gap-3">
                            <button @click="showDeleteModal = false"
                                    class="flex-1 px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium text-sm">
                                Annuler
                            </button>
                            <form :action="`/admin/categories/${deleteId}`" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-semibold text-sm">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </template>

                {{-- Cas : catégorie avec profils → reclassification --}}
                <template x-if="profilesCount > 0">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Reclassifier et supprimer</h3>
                        </div>

                        <p class="text-gray-600 mb-1">
                            La catégorie <strong x-text="deleteName"></strong> contient
                            <strong x-text="profilesCount"></strong> profil(s).
                        </p>
                        <p class="text-gray-500 text-sm mb-6">
                            Choisissez où déplacer ces profils avant la suppression.
                        </p>

                        <form :action="`/admin/categories/${deleteId}/reclassifier`" method="POST">
                            @csrf
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Déplacer les profils vers
                                </label>
                                <select name="target_category_id"
                                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                                    <option value="">— Non classé (catégorie par défaut) —</option>
                                    @foreach($categories as $cat)
                                        <option :disabled="deleteId == {{ $cat->id }}"
                                                :class="deleteId == {{ $cat->id }} ? 'text-gray-300' : ''"
                                                value="{{ $cat->id }}">
                                            {{ $cat->name }} ({{ $cat->profiles_count }} profils)
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1.5">
                                    Si aucune catégorie n'est choisie, une catégorie « Non classé » sera créée automatiquement.
                                </p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" @click="showDeleteModal = false"
                                        class="flex-1 px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium text-sm">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="flex-1 px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-semibold text-sm">
                                    Déplacer et supprimer
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

            </div>
        </div>
    </div>
@endsection
