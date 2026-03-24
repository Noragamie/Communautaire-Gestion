<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - {{ $profile->user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen" x-data="{ showRejectModal: false, rejectReason: '' }">
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
            <a href="{{ route('admin.profiles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Profils
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all">
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
    </main>

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

<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
