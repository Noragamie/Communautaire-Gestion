@extends('layouts.admin')

@section('title', 'Logs — ' . $user->name)

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}"
           class="text-primary-600 hover:text-primary-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Logs de connexion — {{ $user->name }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date & Heure</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Action</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Adresse IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-900">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $log->action === 'login' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $log->action === 'logout' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $log->action === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono text-sm">{{ $log->ip_address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
@endsection
