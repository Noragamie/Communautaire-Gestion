@extends('layouts.app')

@section('title', $actuality->title . ' — Actualités')

@section('content')
<div class="min-w-0">
    <section class="relative w-screen max-w-[100vw] ml-[calc(50%-50vw)] overflow-x-hidden bg-[#2563EB] text-white">
        <div class="relative max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-12 lg:pt-14 lg:pb-16 w-full">
            <nav class="mb-6" aria-label="Fil d’Ariane">
                <a href="{{ route('actualities') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-white/90 hover:text-white transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour aux actualités
                </a>
            </nav>
            <div class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full bg-white/15 text-white text-xs font-semibold backdrop-blur-sm">
                        Actualité
                    </span>
                    @if($actuality->commune)
                        <span class="px-3 py-1 rounded-full bg-white/10 text-white/95 text-xs font-medium">
                            {{ $actuality->commune->name }}
                        </span>
                    @endif
                    @if($actuality->published_at)
                        <span class="text-sm text-white/85 flex items-center gap-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $actuality->published_at->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white leading-tight">
                    {{ $actuality->title }}
                </h1>
                @if($actuality->author)
                    <p class="mt-4 text-sm text-white/80">
                        Publié par <span class="font-medium text-white/95">{{ $actuality->author->name }}</span>
                    </p>
                @endif
            </div>
        </div>
    </section>

    <article class="border-t border-gray-100 bg-gray-50 py-10 lg:py-14">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                @if($actuality->image)
                    <figure class="mb-8 rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-white">
                        <img src="{{ asset('storage/'.$actuality->image) }}"
                             alt=""
                             class="w-full max-h-[min(28rem,70vh)] object-cover">
                    </figure>
                @endif
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-10 lg:p-12">
                    <div class="markdown-body text-gray-800 text-base sm:text-lg">
                        {!! \App\Support\Markdown::toHtml($actuality->content) !!}
                    </div>
                </div>
                <p class="mt-10 text-center">
                    <a href="{{ route('actualities') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Toutes les actualités
                    </a>
                </p>
            </div>
        </div>
    </article>
</div>
@endsection
