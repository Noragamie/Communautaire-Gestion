@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestion des utilisateurs</h1>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nom</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Rôle</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-primary-100 text-primary-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if(!$user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors">
                                            {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.users.logs', $user) }}"
                                   class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors">
                                    Logs
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
