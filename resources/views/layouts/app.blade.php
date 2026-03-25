<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CommunePro')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        
        body { 
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #f9fafb 100%);
            color: #111827;
            min-height: 100vh;
        }
        
        .sidebar-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-item:hover {
            background: rgba(59, 130, 246, 0.05);
            transform: translateX(4px);
        }
        
        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(168, 85, 247, 0.05) 100%);
            color: #2563eb;
            border-left: 3px solid #2563eb;
        }
        
        .notification-badge {
            animation: pulse-badge 2s ease-in-out infinite;
        }
        
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex bg-gray-50">

    <!-- Sidebar -->
    <aside class="w-64 border-r border-gray-200 flex flex-col fixed h-full bg-white z-50 shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-lg">CP</span>
                </div>
                <div class="flex flex-col">
                    <p class="text-base font-bold text-gray-900 leading-tight">CommunePro</p>
                    <p class="text-xs text-gray-500 leading-tight">Gestion communautaire</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto">
            <a href="{{ route('home') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 {{ request()->routeIs('home') ? 'sidebar-item-active text-gray-900' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-sm font-medium">Accueil</span>
            </a>

            <a href="{{ route('annuaire') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 {{ request()->routeIs('annuaire') || request()->routeIs('category.show') ? 'sidebar-item-active text-gray-900' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span class="text-sm font-medium">Annuaire</span>
            </a>

            <a href="{{ route('actualities') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 {{ request()->routeIs('actualities') ? 'sidebar-item-active text-gray-900' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Actualités</span>
            </a>

            @auth
                @if(auth()->user()->isOperateur())
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Mon Espace</p>
                    <a href="{{ route('operator.profile.show') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 {{ request()->routeIs('operator.profile.*') ? 'sidebar-item-active text-gray-900' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="text-sm font-medium">Mon Profil</span>
                    </a>
                    <a href="{{ route('operator.announcements.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 {{ request()->routeIs('operator.announcements.*') ? 'sidebar-item-active text-gray-900' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <span class="text-sm font-medium">Mes Annonces</span>
                    </a>
                </div>
                @endif
                
                @if(auth()->user()->isAdmin())
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Administration</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </div>
                @endif
            @endauth
        </nav>

        <div class="p-4 border-t border-gray-200">
            @auth
                <div class="flex items-center gap-3 px-2 py-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate text-gray-900">{{ auth()->user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-500 hover:text-gray-900 transition-colors">Déconnexion</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block text-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">Connexion</a>
                    <a href="{{ route('register') }}" class="block text-center px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-all">Inscription</a>
                </div>
            @endauth
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 ml-64 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="h-16 border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 bg-white/80 backdrop-blur-md z-40 shadow-sm">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-96">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Rechercher..." class="w-full bg-gray-50 border border-gray-200 rounded-full py-1.5 pl-10 pr-4 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all">
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                @auth
                    <button class="text-gray-400 hover:text-gray-900 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold notification-badge">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                @endauth
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-8 flex-1">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3 text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 text-red-700">
                    <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="p-8 border-t border-gray-200 text-center bg-white">
            <p class="text-gray-500 text-xs">
                &copy; {{ date('Y') }} CommunePro. Tous droits réservés.
            </p>
        </footer>
    </main>

</body>
</html>
