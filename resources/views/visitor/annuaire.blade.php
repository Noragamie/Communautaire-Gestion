@extends('layouts.app')
@section('title', 'Annuaire')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Annuaire par catégories</h1>
    <p class="text-sm text-gray-600">Explorez les profils par catégorie</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($categories as $category)
        <a href="{{ route('category.show', $category) }}" 
           class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover group">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition-all">
                    <svg class="w-5 h-5 text-indigo-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-base text-gray-900 group-hover:text-indigo-600 transition-colors">
                        {{ $category->name }}
                    </h3>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-3">{{ $category->description }}</p>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ $category->profiles_count }} profils</span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    @endforeach
</div>
@endsection
