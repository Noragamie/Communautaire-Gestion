@extends('layouts.admin')

@section('title', 'Profil — ' . $profile->user->name)

@section('content')
    <div x-data="{ showRejectModal: false, rejectReason: '' }">
        <a href="{{ route('admin.profiles.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        @if($profile->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}"
                                 class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-white/20 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="text-white">
                            <h1 class="text-3xl font-bold mb-1">{{ $profile->user->name }}</h1>
                            <p class="text-indigo-100">{{ $profile->secteur_activite }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium
                        {{ $profile->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $profile->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $profile->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($profile->status) }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                @if($profile->motif_rejet)
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-6 mb-6">
                        <p class="font-semibold text-red-800 mb-1">Motif de rejet</p>
                        <p class="text-red-700">{{ $profile->motif_rejet }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Catégorie</p>
                            <p class="font-semibold text-gray-900">{{ $profile->category->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Localisation</p>
                            <p class="font-semibold text-gray-900">{{ $profile->localisation }}</p>
                        </div>
                    </div>
                    @if($profile->telephone)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Téléphone</p>
                                <p class="font-semibold text-gray-900">{{ $profile->telephone }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="font-semibold text-gray-900">{{ $profile->user->email }}</p>
                        </div>
                    </div>
                </div>

                @if($profile->bio)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">À propos</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $profile->bio }}</p>
                    </div>
                @endif

                @if($profile->documents->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">Documents</h3>
                        <div class="space-y-2">
                            @foreach($profile->documents as $doc)
                                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                                   class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $doc->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($profile->status === 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-200 flex gap-4">
                        <form method="POST" action="{{ route('admin.profiles.approve', $profile) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 font-medium transition-all">
                                Approuver
                            </button>
                        </form>
                        <button @click="showRejectModal = true"
                                class="flex-1 bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 font-medium transition-all">
                            Rejeter
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reject Modal -->
        <div x-show="showRejectModal"
             x-cloak
             class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
             @click.self="showRejectModal = false">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Rejeter le profil</h3>
                <form method="POST" action="{{ route('admin.profiles.reject', $profile) }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motif du rejet *</label>
                        <textarea name="motif_rejet" x-model="rejectReason" required rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Expliquez la raison du rejet..."></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" @click="showRejectModal = false"
                                class="flex-1 px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-medium">
                            Annuler
                        </button>
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all font-medium">
                            Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
