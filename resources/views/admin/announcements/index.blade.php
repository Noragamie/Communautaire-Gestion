@extends('layouts.admin')

@section('title', 'Annonces')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Annonces</h1>
            <p class="text-gray-600 mt-1">Visibles uniquement aux utilisateurs avec un profil approuvé</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}"
           class="bg-primary-600 text-white px-6 py-3 rounded-xl hover:bg-primary-700 font-semibold transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer une annonce
        </a>
    </div>

    @if($announcements->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-20 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune annonce pour l'instant</h3>
            <p class="text-gray-600 mb-6">Commencez par créer votre première annonce</p>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">
                Créer la première annonce
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    @else
        <div x-data="{ showDeleteModal: false, deleteId: null, deleteTitle: '' }" class="space-y-4">
            @foreach($announcements as $announcement)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all">
                    <div class="flex items-start gap-6">
                        @if($announcement->image)
                            <img src="{{ asset('storage/' . $announcement->image) }}"
                                 alt="{{ $announcement->title }}"
                                 class="w-24 h-24 rounded-xl object-cover flex-shrink-0 ring-2 ring-gray-100">
                        @else
                            <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-lg text-gray-900 truncate">{{ $announcement->title }}</h3>
                                @if($announcement->is_published)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 flex-shrink-0">
                                        Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 flex-shrink-0">
                                        Brouillon
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mb-3 flex items-center gap-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $announcement->author->name }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $announcement->created_at->format('d/m/Y') }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium text-sm transition-all">
                                Modifier
                            </a>
                            <button @click="showDeleteModal = true; deleteId = {{ $announcement->id }}; deleteTitle = '{{ addslashes($announcement->title) }}'"
                                    class="px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 font-medium text-sm transition-all">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-8">
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
                                    class="w-full px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-semibold text-sm shadow-lg hover:shadow-xl">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
