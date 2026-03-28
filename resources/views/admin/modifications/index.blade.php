@extends('layouts.admin')
@section('title', 'Demandes de modification')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Demandes de modification</h1>
        <p class="text-gray-600 mt-1">Examinez les modifications soumises par les opérateurs</p>
    </div>
    <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-4 py-2 rounded-full">
        {{ $requests->total() }} en attente
    </span>
</div>

@if(!empty($showCommuneFilter))
    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label for="filter_commune_mod" class="block text-xs font-medium text-gray-500 mb-1">Filtrer par commune</label>
            <select id="filter_commune_mod" name="commune_id" onchange="this.form.submit()"
                    class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-primary-500 min-w-[12rem]">
                <option value="">Toutes (périmètre)</option>
                @foreach($managedCommunesForFilter as $c)
                    <option value="{{ $c->id }}" @selected((string) request('commune_id') === (string) $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </form>
@endif

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm text-green-800">{{ session('success') }}</p>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    @if($requests->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-medium">Aucune demande en attente</p>
        </div>
    @else
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Opérateur</th>
                    @if(auth()->user()->isAdmin())
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Commune</th>
                    @endif
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Catégorie demandée</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Soumis le</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Documents</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($requests as $req)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $req->profile->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $req->profile->user->email }}</div>
                    </td>
                    @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $req->profile->user->commune?->name ?? '—' }}</td>
                    @endif
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $req->profile->category->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $req->documents->count() }} fichier(s)</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.modifications.show', $req) }}"
                           class="inline-flex items-center gap-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Examiner
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
