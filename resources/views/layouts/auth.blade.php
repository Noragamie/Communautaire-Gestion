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
<body class="antialiased min-h-screen">

    <!-- Simple Header -->
    <header class="bg-white border-b border-gray-200">
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

                <!-- Back to Home -->
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

</body>
</html>
