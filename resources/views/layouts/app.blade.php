<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Communautaire')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900">
                        Gestion Communautaire
                    </a>
                    <div class="hidden md:flex gap-6">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Accueil</a>
                        <a href="{{ route('annuaire') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Annuaire</a>
                        <a href="{{ route('actualities') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Actualités</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Administration
                            </a>
                        @elseif(auth()->user()->isOperateur())
                            <a href="{{ route('operator.profile.show') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Mon profil
                            </a>
                            <a href="{{ route('operator.announcements.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Annonces
                            </a>
                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open"
                                        class="relative p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                                    @php $notifications = auth()->user()->notifications()->latest()->take(6)->get(); @endphp

                                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                        <span class="font-semibold text-sm text-gray-900">Notifications</span>
                                        @if($unreadCount > 0)
                                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                                    Tout marquer comme lu
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if($notifications->isEmpty())
                                        <div class="px-4 py-8 text-center text-sm text-gray-400">
                                            Aucune notification
                                        </div>
                                    @else
                                        <div class="divide-y divide-gray-50">
                                            @foreach($notifications as $notif)
                                                <a href="{{ route('notifications.read', $notif->id) }}"
                                                   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors
                                                          {{ is_null($notif->read_at) ? 'bg-indigo-50/40' : '' }}">
                                                    <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0
                                                                {{ is_null($notif->read_at) ? 'bg-indigo-500' : 'bg-gray-300' }}"></div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm text-gray-800 leading-snug">{{ $notif->data['message'] }}</p>
                                                        @if(!empty($notif->data['motif']))
                                                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $notif->data['motif'] }}</p>
                                                        @endif
                                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium text-sm">
                            Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-3 text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-red-800">{{ $errors->first('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <p class="text-center text-gray-500 text-xs">
                © {{ date('Y') }} Gestion Communautaire. Tous droits réservés.
            </p>
        </div>
    </footer>

</body>
</html>
