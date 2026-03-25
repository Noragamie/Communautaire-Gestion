@props(['profile'])

<div class="glass-effect rounded-2xl overflow-hidden hover-lift">
    <!-- Cover Image -->
    <div class="h-32 bg-gradient-to-br from-primary-500 via-primary-600 to-accent-600 relative">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE0YzMuMzEgMCA2LTIuNjkgNi02cy0yLjY5LTYtNi02LTYgMi42OS02IDYgMi42OSA2IDYgNnptLTEyIDZjMi4yMSAwIDQtMS43OSA0LTRzLTEuNzktNC00LTQtNCAxLjc5LTQgNCAxLjc5IDQgNCA0eiIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>
    </div>
    
    <!-- Profile Content -->
    <div class="p-6 -mt-16 relative">
        <!-- Avatar -->
        <div class="mb-4">
            @if($profile->photo)
                <img src="{{ asset('storage/'.$profile->photo) }}" 
                     alt="{{ $profile->user->name }}" 
                     class="w-24 h-24 rounded-2xl object-cover ring-4 ring-dark-800 mx-auto">
            @else
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center ring-4 ring-dark-800 mx-auto">
                    <span class="text-3xl font-bold text-white">
                        {{ substr($profile->user->name, 0, 1) }}
                    </span>
                </div>
            @endif
        </div>
        
        <!-- Info -->
        <div class="text-center mb-4">
            <h3 class="text-xl font-bold text-white mb-1">{{ $profile->user->name }}</h3>
            <p class="text-gray-300 text-sm mb-2">{{ $profile->secteur_activite }}</p>
            
            @if($profile->localisation)
                <div class="flex items-center justify-center gap-1 text-gray-400 text-sm mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $profile->localisation }}</span>
                </div>
            @endif
            
            @if($profile->category)
                <x-badge type="primary">{{ $profile->category->name }}</x-badge>
            @endif
        </div>
        
        <!-- Description -->
        @if($profile->description)
            <p class="text-gray-400 text-sm mb-4 line-clamp-3">
                {{ $profile->description }}
            </p>
        @endif
        
        <!-- Action Button -->
        <a href="{{ route('profiles.show', $profile) }}" class="block w-full btn-primary text-center">
            Voir le profil complet
        </a>
    </div>
</div>
