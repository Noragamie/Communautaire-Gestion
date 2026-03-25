<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Communautaire')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#0066FF',
                        'light-blue': '#F0F7FF',
                        'sky-blue': '#E0F2FE',
                    },
                    boxShadow: {
                        'soft': '0 2px 8px rgba(0, 102, 255, 0.08)',
                        'medium': '0 4px 16px rgba(0, 102, 255, 0.12)',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif; }
        body { background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%); }
        .nav-active { background: #E0F2FE; color: #0066FF; border-left: 3px solid #0066FF; }
        .btn-primary { background: linear-gradient(135deg, #0066FF 0%, #0052CC 100%); }
        .btn-primary:hover { box-shadow: 0 8px 24px rgba(0, 102, 255, 0.3); }
        .card-glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(0, 102, 255, 0.1); }
        .gradient-text { background: linear-gradient(135deg, #0066FF 0%, #0052CC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex bg-white text-gray-900">

    <!-- Sidebar Navigation -->
    <aside class="w-64 border-r border-blue-100 fixed h-screen flex flex-col z-40 overflow-y-auto bg-white">
        <!-- Logo -->
        <div class="p-6 border-b border-blue-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg btn-primary flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Communautaire</h1>
                    <p class="text-xs text-blue-600 font-medium">Plateforme</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-blue-600 transition-all {{ request()->routeIs('home') ? 'nav-active' : 'hover:bg-blue-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3"/></svg>
                <span class="text-sm font-medium">Tableau de Bord</span>
            </a>

            <a href="{{ route('annuaire') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-blue-600 transition-all {{ request()->routeIs('annuaire') ? 'nav-active' : 'hover:bg-blue-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-sm font-medium">Membres</span>
            </a>

            <a href="{{ route('actualities') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-blue-600 transition-all {{ request()->routeIs('actualities') ? 'nav-active' : 'hover:bg-blue-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Événements</span>
            </a>

            <a href="{{ route('discussions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-blue-600 transition-all {{ request()->routeIs('discussions') ? 'nav-active' : 'hover:bg-blue-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <span class="text-sm font-medium">Discussions</span>
            </a>

            @auth
                @if(auth()->user()->isOperateur())
                <div class="pt-4 mt-4 border-t border-blue-100">
                    <p class="px-4 text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Mon Espace</p>
                    <a href="{{ route('operator.profile.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-blue-600 transition-all {{ request()->routeIs('operator.profile.*') ? 'nav-active' : 'hover:bg-blue-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="text-sm font-medium">Mon Profil</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->isAdmin())
                <div class="pt-4 mt-4 border-t border-blue-100">
                    <p class="px-4 text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Administration</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:text-blue-600 transition-all hover:bg-blue-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span class="text-sm font-medium">Paramètres</span>
                    </a>
                </div>
                @endif
            @endauth
        </nav>

        <!-- User Profile Section -->
        <div class="p-4 border-t border-blue-100">
            @auth
                <div class="card-glass rounded-lg p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg btn-primary flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm font-medium transition-all">
                        Déconnexion
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block w-full px-4 py-2 rounded-lg border border-blue-300 text-blue-600 text-sm font-medium hover:bg-blue-50 transition-all text-center">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="block w-full px-4 py-2 rounded-lg btn-primary text-white text-sm font-medium hover:shadow-medium transition-all text-center">
                        Inscription
                    </a>
                </div>
            @endauth
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="h-20 border-b border-blue-100 sticky top-0 z-30 flex items-center justify-between px-8 bg-white shadow-soft">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-96">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Rechercher..." class="w-full bg-blue-50 border border-blue-200 rounded-lg py-2 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="flex items-center gap-6">
                @auth
                    <button class="relative p-2 text-gray-600 hover:text-blue-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </button>
                @endauth

                <div class="w-10 h-10 rounded-lg btn-primary cursor-pointer hover:shadow-medium transition-all"></div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-300 rounded-lg p-4 flex items-center gap-3 text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-300 rounded-lg p-4 flex items-start gap-3 text-red-700">
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
        <footer class="border-t border-blue-100 p-8 text-center text-gray-600 text-xs bg-blue-50/30">
            <p>&copy; {{ date('Y') }} Communautaire. Tous droits réservés.</p>
        </footer>
    </main>

</body>
</html>
