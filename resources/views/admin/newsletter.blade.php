@extends('layouts.admin')

@section('title', 'Newsletter')

@section('content')
<div class="space-y-6">

    <h1 class="text-3xl font-bold text-gray-900">Newsletter</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 font-medium">Abonnés actifs</p>
            <p class="text-3xl font-bold text-primary-600 mt-1">{{ $stats['actifs'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 font-medium">Désabonnés</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['desabonnes'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 font-medium">Total inscrits</p>
            <p class="text-3xl font-bold text-gray-700 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 font-medium">Avec compte</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['avec_compte'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 font-medium">Visiteurs</p>
            <p class="text-3xl font-bold text-gray-500 mt-1">{{ $stats['anonymes'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500 font-medium">Nouveaux ce mois</p>
            <p class="text-3xl font-bold text-accent-600 mt-1">{{ $stats['ce_mois'] }}</p>
        </div>
    </div>

    {{-- Liste --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Liste des abonnés</h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-left">Inscrit le</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscribers as $sub)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $sub->email }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $sub->user?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->user_id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Compte lié
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Anonyme
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->subscribed)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                                    Abonné
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                    Désabonné
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $sub->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            Aucun abonné pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($subscribers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
