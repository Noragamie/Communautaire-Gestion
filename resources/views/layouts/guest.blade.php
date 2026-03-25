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
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-lg">CP</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-base font-bold text-gray-900 leading-tight">CommunePro</p>
                        <p class="text-xs text-gray-500 leading-tight">Gestion communautaire</p>
                    </div>
                </a>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Accueil</a>
                    <a href="{{ route('annuaire') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Annuaire</a>
                    <a href="{{ route('actualities') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Actualités</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('operator.profile.show') }}" 
                           class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                            Mon espace
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            S'inscrire
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-200 bg-white" x-data="{ mobileMenuOpen: false }">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ route('home') }}" class="block text-sm font-medium text-gray-700 hover:text-primary-600">Accueil</a>
                <a href="{{ route('annuaire') }}" class="block text-sm font-medium text-gray-700 hover:text-primary-600">Annuaire</a>
                <a href="{{ route('actualities') }}" class="block text-sm font-medium text-gray-700 hover:text-primary-600">Actualités</a>
                @guest
                    <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-700 hover:text-primary-600">Connexion</a>
                    <a href="{{ route('register') }}" class="block text-sm font-medium text-primary-600">S'inscrire</a>
                @endguest
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">CP</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">CommunePro</p>
                            <p class="text-xs text-gray-500">Gestion communautaire</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Plateforme de gestion communautaire pour les acteurs économiques de la commune. 
                        Rejoignez notre réseau et développez votre activité.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Navigation</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-primary-600 transition-colors">Accueil</a></li>
                        <li><a href="{{ route('annuaire') }}" class="text-sm text-gray-600 hover:text-primary-600 transition-colors">Annuaire</a></li>
                        <li><a href="{{ route('actualities') }}" class="text-sm text-gray-600 hover:text-primary-600 transition-colors">Actualités</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            contact@communepro.bj
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +229 XX XX XX XX
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Cotonou, Bénin
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} CommunePro. Tous droits réservés.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-sm text-gray-500 hover:text-primary-600 transition-colors">Politique de confidentialité</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-primary-600 transition-colors">Conditions d'utilisation</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-primary-600 transition-colors">Mentions légales</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
