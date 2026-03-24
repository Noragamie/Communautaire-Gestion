@extends('layouts.app')
@section('title', 'Actualités')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Actualités</h1>
    <p class="text-sm text-gray-600">Restez informé des dernières nouvelles</p>
</div>

<div class="space-y-6">
    @forelse($actualities as $actuality)
        <article class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden card-hover">
            @if($actuality->image)
                <img src="{{ asset('storage/'.$actuality->image) }}" alt="{{ $actuality->title }}" 
                     class="w-full h-64 object-cover">
            @endif
            <div class="p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $actuality->title }}</h2>
                <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $actuality->published_at->format('d/m/Y') }}
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $actuality->author->name }}
                    </span>
                </div>
                <div class="text-sm text-gray-700 leading-relaxed">{!! nl2br(e($actuality->content)) !!}</div>
            </div>
        </article>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <p class="text-sm text-gray-500">Aucune actualité publiée</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $actualities->links() }}
</div>

<!-- Newsletter -->
<div class="mt-12 bg-white rounded-xl shadow-sm border border-gray-200 p-6" x-data="{ loading: false }">
    <div class="flex items-center gap-4 mb-4">
        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-bold text-gray-900">Newsletter</h3>
            <p class="text-xs text-gray-600">Recevez les actualités par email</p>
        </div>
    </div>
    <form method="POST" action="{{ route('newsletter.subscribe') }}" @submit="loading = true" class="flex gap-4">
        @csrf
        <input type="email" name="email" placeholder="votre@email.com" required
               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
        <button type="submit" 
                :disabled="loading"
                class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium transition-all disabled:opacity-50 text-sm">
            <span x-show="!loading">S'abonner</span>
            <span x-show="loading" x-cloak>...</span>
        </button>
    </form>
</div>
@endsection
