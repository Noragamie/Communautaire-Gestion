@extends('layouts.admin')

@section('title', 'Profils')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Gestion des profils</h1>
        <a href="{{ route('admin.export.excel') }}"
           class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 font-medium transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exporter
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher..."
                   class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <select name="status" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 font-medium transition-all">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Profiles Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Utilisateur</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Catégorie</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Localisation</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($profiles as $profile)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
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
                                <span class="font-medium text-gray-900">{{ $profile->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $profile->category->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $profile->localisation }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $profile->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $profile->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $profile->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($profile->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.profiles.show', $profile) }}"
                               class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                                Détails
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $profiles->links() }}
    </div>
@endsection
