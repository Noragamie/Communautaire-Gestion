<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') — CommunePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.fontshare.com" crossorigin>
    <style>
        body { 
            background: #fff;
        }
        [x-cloak] { display: none !important; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); }
    </style>
    @stack('styles')
</head>
<body class="antialiased min-h-screen font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-66 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 sticky top-0 h-screen">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <p class="font-bold text-xl text-gray-900">Administration</p>
                <div class="relative"
                 x-data="{
                     open: false,
                     count: 0,
                     notifications: [],
                     init() {
                         this.fetch();
                         setInterval(() => this.fetch(), 30000);
                     },
                     fetch() {
                         window.fetch('{{ route('notifications.data') }}')
                             .then(r => r.json())
                             .then(data => {
                                 this.count = data.count;
                                 this.notifications = data.notifications;
                             });
                     },
                     markAllRead() {
                         fetch('{{ route('notifications.read-all') }}', {
                             method: 'POST',
                             headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                         }).then(() => { this.count = 0; this.notifications.forEach(n => n.read = true); });
                     }
                 }"
                 @click.outside="open = false">
                <button @click="open = !open"
                        class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-all overflow-visible">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-cloak x-show="count > 0"
                          x-text="count > 9 ? '9+' : count"
                          class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                    </span>
                </button>

                <!-- Dropdown notifications -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50"
                     style="display:none">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <span class="font-semibold text-sm text-gray-900">Notifications</span>
                        <button x-show="count > 0" @click="markAllRead()"
                                class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                            Tout marquer comme lu
                        </button>
                    </div>

                    <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="px-4 py-8 text-center text-sm text-gray-400">Aucune notification</div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <a :href="notif.url"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
                               :class="!notif.read ? 'bg-primary-50/40' : ''">
                                <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0"
                                     :class="!notif.read ? 'bg-primary-500' : 'bg-gray-300'"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-800 leading-snug" x-text="notif.message"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="notif.date"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        @auth
            @if(auth()->user()->isAdmin() && auth()->user()->managedCommunes()->count() === 0)
                <div class="mx-4 mt-3 p-3 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-900">
                    Aucune commune assignée. Exécutez le seeder ou associez des communes au compte administrateur (table <code class="font-mono">admin_commune</code>).
                </div>
            @elseif(auth()->user()->isBackoffice())
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/80 text-sm">
                    @if(auth()->user()->isAdmin() && isset($adminManagedCommunes) && $adminManagedCommunes->count() > 0)
                        <form method="POST" action="{{ route('admin.commune.active') }}" class="flex flex-col gap-2">
                            @csrf
                            <label for="admin_commune_select" class="text-xs font-medium text-gray-500 uppercase tracking-wide">Périmètre</label>
                            <select id="admin_commune_select" name="commune_id" onchange="this.form.submit()"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 bg-white">
                                <option value="all" @selected(!empty($adminViewingAllManagedCommunes))>
                                    Toutes mes communes
                                </option>
                                @foreach($adminManagedCommunes as $c)
                                    <option value="{{ $c->id }}"
                                        @selected(empty($adminViewingAllManagedCommunes) && isset($adminActiveCommuneModel) && $adminActiveCommuneModel && (int) $adminActiveCommuneModel->id === (int) $c->id)>
                                        {{ $c->name }}@if($c->department_name) — {{ $c->department_name }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        <p class="text-gray-600">
                            <span class="text-gray-500">Territoire :</span>
                            <span class="font-semibold text-gray-900">
                                {{ $adminActiveCommuneModel->name ?? auth()->user()->commune?->name ?? '—' }}
                            </span>
                        </p>
                    @endif
                </div>
            @endif
        @endauth

        <nav class="p-4 space-y-1 flex-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Tableau de bord
            </a>

            <a href="{{ route('admin.profiles.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.profiles.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Profils
            </a>

            <a href="{{ route('admin.modifications.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.modifications.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifications
                @if(($adminLayoutPendingModifications ?? 0) > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $adminLayoutPendingModifications }}
                    </span>
                @endif
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.categories.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Catégories
            </a>
            @endif

            <a href="{{ route('admin.actualities.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.actualities.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Actualités économiques
            </a>

            <a href="{{ route('admin.announcements.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.announcements.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Annonces
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.newsletter') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.newsletter') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Newsletter
            </a>
            @endif

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Utilisateurs
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.logs') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.logs*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Journal des logs
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-gray-200 space-y-1">
            <a href="{{ route('admin.settings') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->routeIs('admin.settings*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Paramètres
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all w-full text-sm font-medium">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 min-w-0">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl px-6 py-4">
                <div class="flex items-center gap-3 text-green-800">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-6 py-4">
                <div class="flex items-center gap-3 text-red-800">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
