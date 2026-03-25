@extends('layouts.admin')

@section('title', 'Annonces')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Annonces</h1>
            <p class="text-sm text-gray-500 mt-1">Visibles uniquement aux utilisateurs avec un profil approuvé</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}"
           class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 font-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer une annonce
        </a>
    </div>

    @if($announcements->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <p class="text-gray-500 font-medium">Aucune annonce pour l'instant</p>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-block mt-4 text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                Créer la première annonce →
            </a>
        </div>
    @else
        <div x-data="{ showDeleteModal: false, deleteId: null, deleteTitle: '' }" class="space-y-4">
            @foreach($announcements as $announcement)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                    <div class="flex items-start gap-5">
                        @if($announcement->image)
                            <img src="{{ asset('storage/' . $announcement->image) }}"
                                 alt="{{ $announcement->title }}"
                                 class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-gray-900 truncate">{{ $announcement->title }}</h3>
                                @if($announcement->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">
                                        Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 flex-shrink-0">
                                        Brouillon
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mb-2">
                                Par {{ $announcement->author->name }} · {{ $announcement->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($announcement->content, 120) }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition-all">
                                Modifier
                            </a>
                            <button @click="showDeleteModal = true; deleteId = {{ $announcement->id }}; deleteTitle = '{{ addslashes($announcement->title) }}'"
                                    class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium text-sm transition-all">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $announcements->links() }}
            </div>

            <!-- Modal suppression -->
            <div x-show="showDeleteModal"
                 x-cloak
                 class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
                 @click.self="showDeleteModal = false">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Supprimer l'annonce</h3>
                    <p class="text-gray-600 mb-6">
                        Supprimer <strong x-text="deleteTitle"></strong> ? Cette action est irréversible.
                    </p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false"
                                class="flex-1 px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium text-sm">
                            Annuler
                        </button>
                        <form :action="`{{ url('admin/annonces') }}/${deleteId}`" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-medium text-sm">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
