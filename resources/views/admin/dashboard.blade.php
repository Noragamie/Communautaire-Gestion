@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Tableau de bord</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-600">Total profils</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-600">En attente</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['approved'] }}</p>
            <p class="text-xs text-gray-600">Approuvés</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-gray-600">Rejetés</p>
        </div>
    </div>

    <!-- Recent Profiles -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6">Profils récents</h2>
        <div class="space-y-3">
            @foreach($recentProfiles as $profile)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                    <div class="flex items-center gap-4">
                        @if($profile->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" alt="{{ $profile->user->name }}"
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-sm text-gray-900">{{ $profile->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $profile->secteur_activite }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $profile->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $profile->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $profile->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($profile->status) }}
                        </span>
                        <a href="{{ route('admin.profiles.show', $profile) }}"
                           class="text-indigo-600 hover:text-indigo-700 font-medium text-xs">
                            Voir
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
