@extends('layouts.admin')

@section('title', 'Actualités')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Actualités économiques</h1>
            <p class="text-gray-600 mt-1">Gérez les événements et actualités de la communauté</p>
        </div>
        <a href="{{ route('admin.actualities.create') }}"
           class="bg-primary-600 text-white px-6 py-3 rounded-xl hover:bg-primary-700 font-semibold transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer une actualité
        </a>
    </div>

    @if($actualities->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-20 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune actualité pour l'instant</h3>
            <p class="text-gray-600 mb-6">Commencez par créer votre première actualité</p>
            <a href="{{ route('admin.actualities.create') }}"
               class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">
                Créer la première actualité
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    @else
        <div x-data="{ showDeleteModal: false, deleteId: null, deleteTitle: '' }" class="space-y-4">
            @foreach($actualities as $actuality)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                    <div class="flex items-start gap-5">
                        @if($actuality->image)
                            <img src="{{ asset('storage/' . $actuality->image) }}"
                                 alt="{{ $actuality->title }}"
                                 class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-gray-900 truncate">{{ $actuality->title }}</h3>
                                @if($actuality->is_published)
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
                                Par {{ $actuality->author->name }} · {{ $actuality->created_at->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($actuality->content, 120) }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.actualities.edit', $actuality) }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium text-sm transition-all">
                                Modifier
                            </a>
                            @if(!$actuality->is_published)
                                <form method="POST" action="{{ route('admin.actualities.publish', $actuality) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm transition-all">
                                        Publier
                                    </button>
                                </form>
                            @endif
                            <button @click="showDeleteModal = true; deleteId = {{ $actuality->id }}; deleteTitle = '{{ addslashes($actuality->title) }}'"
                                    class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium text-sm transition-all">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $actualities->links() }}
            </div>

            <!-- Modal suppression -->
            <div x-show="showDeleteModal"
                 x-cloak
                 class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
                 @click.self="showDeleteModal = false">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Supprimer l'actualité</h3>
                    <p class="text-gray-600 mb-6">
                        Supprimer <strong x-text="deleteTitle"></strong> ? Cette action est irréversible.
                    </p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false"
                                class="flex-1 px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium text-sm">
                            Annuler
                        </button>
                        <form :action="`{{ url('admin/actualites') }}/${deleteId}`" method="POST" class="flex-1">
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
