@extends('layouts.app')

@section('title', $announcement->title . ' — Annonce')

@section('content')
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 rounded-2xl border border-gray-100">
    <div class="max-w-3xl mx-auto">
        <nav class="mb-6" aria-label="Fil d’Ariane">
            <a href="{{ route('operator.announcements.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux annonces
            </a>
        </nav>

        <article class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            @if($announcement->image)
                <div class="aspect-[21/9] max-h-80 w-full overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/'.$announcement->image) }}"
                         alt=""
                         class="w-full h-full object-cover">
                </div>
            @endif
            <div class="p-6 sm:p-8 lg:p-10">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-100">
                        Annonce
                    </span>
                    @if($announcement->commune)
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            {{ $announcement->commune->name }}
                        </span>
                    @endif
                    <span class="text-sm text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $announcement->published_at?->format('d/m/Y à H:i') }}
                    </span>
                    @if($announcement->author)
                        <span class="text-sm text-gray-500">
                            · {{ $announcement->author->name }}
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight mb-6">
                    {{ $announcement->title }}
                </h1>
                <div class="markdown-body text-gray-800 text-base leading-relaxed">
                    {!! \App\Support\Markdown::toHtml($announcement->content) !!}
                </div>
            </div>
        </article>
    </div>
</div>
@endsection
