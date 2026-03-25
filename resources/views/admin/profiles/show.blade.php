@extends('layouts.admin')

@section('title', 'Profil — ' . $profile->user->name)

@section('content')
    <div x-data="{ showRejectModal: false, rejectReason: '' }">
        <!-- Back Button -->
        <a href="{{ route('admin.profiles.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white mb-6 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour à la liste
        </a>

        <!-- Profile Card -->
        <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-sm border border-gray-200 dark:border-white/10 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-accent-blue to-accent-blue/80 p-8">
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
                            <p class="text-white/80">{{ $profile->secteur_activite }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $profile->status === 'approved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : '' }}
                        {{ $profile->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200' : '' }}
                        {{ $profile->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' : '' }}">
                        {{ $profile->status === 'pending' ? 'En attente' : ($profile->status === 'approved' ? 'Approuvé' : 'Rejeté') }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Rejection Reason -->
                @if($profile->motif_rejet)
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-xl p-6 mb-6">
                        <p class="font-semibold text-red-800 dark:text-red-200 mb-1">Motif de rejet</p>
                        <p class="text-red-700 dark:text-red-300">{{ $profile->motif_rejet }}</p>
                    </div>
                @endif

                <!-- Profile Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-accent-blue/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Catégorie</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $profile->category->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-accent-blue/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Localisation</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $profile->localisation }}</p>
                        </div>
                    </div>

                    @if($profile->telephone)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-accent-blue/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-accent-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Téléphone</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $profile->telephone }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-accent-blue/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Email</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $profile->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                @if($profile->bio)
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-white/10">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">À propos</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $profile->bio }}</p>
                    </div>
                @endif

                <!-- Documents Section -->
                @if($profile->documents->count() > 0)
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-white/10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-lg">Documents</h3>
                            <form method="POST" action="{{ route('admin.profiles.export-documents', $profile) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-accent-blue hover:bg-accent-blue/90 text-white rounded-lg font-medium transition-colors text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Exporter tous les documents
                                </button>
                            </form>
                        </div>

                        <div class="space-y-3">
                            @foreach($profile->documents as $doc)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-dark-700 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-600 transition-colors">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        @if(in_array(pathinfo($doc->path, PATHINFO_EXTENSION), ['pdf']))
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5z"/>
                            <path d="M6 6a1 1 0 100 2 1 1 0 000-2zM6 10a1 1 0 100 2 1 1 0 000-2zM6 14a1 1 0 100 2 1 1 0 000-2z"/>
                                            </svg>
                                        @elseif(in_array(pathinfo($doc->path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $doc->original_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($doc->size / 1024, 2) }} KB • {{ ucfirst($doc->type) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/'.$doc->path) }}" target="_blank" download
                                       class="ml-4 px-3 py-2 text-accent-blue hover:text-accent-blue/80 font-medium text-sm transition-colors flex-shrink-0">
                                        Télécharger
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if($profile->status === 'pending')
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-white/10 flex gap-4">
                        <form method="POST" action="{{ route('admin.profiles.approve', $profile) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Approuver le profil
                                </span>
                            </button>
                        </form>
                        <button @click="showRejectModal = true"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Rejeter le profil
                        </button>
                    </div>
                @elseif($profile->status === 'rejected')
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-white/10">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">L'utilisateur peut resoummettre son profil après correction.</p>
                        <form method="POST" action="{{ route('admin.profiles.approve', $profile) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                                Approuver ce profil
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reject Modal -->
        <div x-show="showRejectModal"
             x-cloak
             class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
             @click.self="showRejectModal = false">
            <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 border border-gray-200 dark:border-white/10">
                <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Rejeter le profil</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Veuillez expliquer la raison du rejet. L'utilisateur recevra ce message et pourra resoummettre son profil.</p>

                <form method="POST" action="{{ route('admin.profiles.reject', $profile) }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Motif du rejet *</label>
                        <textarea name="motif_rejet" x-model="rejectReason" required rows="4"
                                  class="w-full px-4 py-3 bg-white dark:bg-dark-700 border border-gray-300 dark:border-white/10 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-gray-900 dark:text-white"
                                  placeholder="Expliquez précisément les raisons du rejet..."></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" @click="showRejectModal = false"
                                class="flex-1 px-6 py-3 border border-gray-300 dark:border-white/10 rounded-xl hover:b
(Content truncated due to size limit. Use line ranges to read remaining content)

