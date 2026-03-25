@extends('layouts.guest')
@section('title', 'Calendrier Communautaire - CommunePro')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Calendrier Communautaire</h1>
            <p class="text-gray-600">Découvrez et rejoignez les prochains moments forts de notre collectif.</p>
        </div>

        <!-- Month Selector -->
        @if($months->isNotEmpty())
        <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
            <div class="flex flex-wrap bg-white rounded-xl p-1 border border-gray-200 shadow-sm gap-1">
                <a href="{{ route('actualities') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('month') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }} transition-all">
                    Tous
                </a>
                @foreach($months->take(5) as $month)
                    <a href="{{ route('actualities', ['month' => $month['value']]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ request('month') == $month['value'] ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }} transition-all">
                        {{ $month['label'] }}
                    </a>
                @endforeach
            </div>
            <div class="flex bg-white rounded-xl p-1 border border-gray-200 shadow-sm">
                <button class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </button>
                <button class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Events List -->
        <div class="space-y-6">
            @forelse($actualities as $actuality)
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-xl transition-all">
                <div class="md:w-1/3 h-64 md:h-auto overflow-hidden">
                    @if($actuality->image)
                        <img src="{{ asset('storage/'.$actuality->image) }}" 
                             alt="{{ $actuality->title }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="md:w-2/3 p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold">
                                Événement
                            </span>
                            <span class="text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $actuality->published_at->format('d M Y à H:i') }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $actuality->title }}</h2>
                        <p class="text-gray-600 leading-relaxed line-clamp-3">
                            {!! strip_tags($actuality->content) !!}
                        </p>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex -space-x-2">
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-primary-500 to-primary-600"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-accent-500 to-accent-600"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-green-500 to-green-600"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-primary-600 flex items-center justify-center text-xs font-bold text-white">
                                +12
                            </div>
                        </div>
                        <button class="px-6 py-2 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-all">
                            S'inscrire
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-20 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun événement prévu</h3>
                <p class="text-gray-600">Revenez plus tard pour découvrir les nouvelles activités.</p>
            </div>
            @endforelse
        </div>

        @if($actualities->hasPages())
        <div class="mt-12">
            {{ $actualities->links() }}
        </div>
        @endif

        <!-- Newsletter Section -->
        <div class="mt-16 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-8 md:p-12 text-center shadow-xl">
            <div class="max-w-2xl mx-auto">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-3">Restez informé</h2>
                <p class="text-white/90 mb-8">Recevez les dernières actualités et événements directement dans votre boîte mail.</p>
                
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    @csrf
                    <input type="email" 
                           name="email" 
                           placeholder="Votre adresse email" 
                           required
                           class="flex-1 px-6 py-4 rounded-xl border-2 border-white/20 bg-white/10 backdrop-blur-sm text-white placeholder-white/60 focus:outline-none focus:border-white/40 transition-all">
                    <button type="submit" 
                            class="px-8 py-4 bg-white text-primary-600 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg">
                        S'abonner
                    </button>
                </form>
                
                <p class="text-white/70 text-sm mt-4">
                    En vous abonnant, vous acceptez de recevoir nos communications. Vous pouvez vous désabonner à tout moment.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
