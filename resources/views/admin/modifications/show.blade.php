@extends('layouts.admin')
@section('title', 'Demande de modification')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.modifications.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux demandes
    </a>
</div>

<div class="flex items-center justify-between mb-8" x-data="{ showReject: false }">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Demande de modification</h1>
        <p class="text-gray-600 mt-1">{{ $modificationRequest->profile->user->name }} — soumis le {{ $modificationRequest->created_at->format('d/m/Y à H:i') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('admin.modifications.approve', $modificationRequest) }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Approuver cette modification ?')"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-colors">
                Approuver
            </button>
        </form>
        <div>
            <button @click="showReject = !showReject"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-colors">
                Refuser
            </button>
            <div x-show="showReject" x-cloak
                 class="absolute right-8 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg p-4 w-96 z-10">
                <form method="POST" action="{{ route('admin.modifications.reject', $modificationRequest) }}">
                    @csrf
                    <p class="text-sm font-semibold text-gray-700 mb-2">Motif du refus</p>
                    <textarea name="motif" rows="3" required placeholder="Expliquez le motif du refus..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent mb-3"></textarea>
                    <button type="submit"
                            class="w-full bg-red-700 hover:bg-red-800 text-white py-2 rounded-lg text-sm font-semibold transition-colors">
                        Confirmer le refus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        @foreach($errors->all() as $error)
            <p class="text-sm text-red-800">{{ $error }}</p>
        @endforeach
    </div>
@endif

{{-- Comparaison côte à côte --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Version actuelle --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
            Version actuelle (publique)
        </h2>
        @php $profile = $modificationRequest->profile; @endphp
        <dl class="space-y-3 text-sm">
            <div><dt class="font-semibold text-gray-500">Catégorie</dt><dd class="text-gray-900">{{ $profile->category->name ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Bio</dt><dd class="text-gray-900">{{ $profile->bio ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Compétences</dt><dd class="text-gray-900 whitespace-pre-wrap">{{ $profile->competences ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Expérience</dt><dd class="text-gray-900 whitespace-pre-wrap">{{ $profile->experience ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Localisation</dt><dd class="text-gray-900">{{ $profile->localisation ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Secteur</dt><dd class="text-gray-900">{{ $profile->secteur_activite ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Téléphone</dt><dd class="text-gray-900">{{ $profile->telephone ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Site web</dt><dd class="text-gray-900">{{ $profile->site_web ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Niveau d'étude</dt><dd class="text-gray-900">{{ $profile->niveau_etude ?? '—' }}</dd></div>
        </dl>
        @if($profile->photo)
            <div class="mt-4">
                <p class="font-semibold text-gray-500 text-sm mb-2">Photo actuelle</p>
                <img src="{{ Storage::url($profile->photo) }}" class="w-20 h-20 rounded-full object-cover">
            </div>
        @endif
        @if($profile->documents->isNotEmpty())
            <div class="mt-4">
                <p class="font-semibold text-gray-500 text-sm mb-2">Documents actuels</p>
                <ul class="space-y-1">
                    @foreach($profile->documents as $doc)
                        <li class="text-sm text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $doc->original_name }} <span class="text-gray-400">({{ $doc->type }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Version demandée --}}
    <div class="bg-yellow-50 rounded-2xl shadow-sm border border-yellow-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>
            Version demandée
        </h2>
        @php $data = $modificationRequest->data; @endphp
        <dl class="space-y-3 text-sm">
            <div><dt class="font-semibold text-gray-500">Catégorie</dt><dd class="text-gray-900">{{ \App\Models\Category::find($data['category_id'])?->name ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Bio</dt><dd class="text-gray-900">{{ $data['bio'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Compétences</dt><dd class="text-gray-900 whitespace-pre-wrap">{{ $data['competences'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Expérience</dt><dd class="text-gray-900 whitespace-pre-wrap">{{ $data['experience'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Localisation</dt><dd class="text-gray-900">{{ $data['localisation'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Secteur</dt><dd class="text-gray-900">{{ $data['secteur_activite'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Téléphone</dt><dd class="text-gray-900">{{ $data['telephone'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Site web</dt><dd class="text-gray-900">{{ $data['site_web'] ?? '—' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Niveau d'étude</dt><dd class="text-gray-900">{{ $data['niveau_etude'] ?? '—' }}</dd></div>
        </dl>
        @if($modificationRequest->new_photo)
            <div class="mt-4">
                <p class="font-semibold text-gray-500 text-sm mb-2">Nouvelle photo</p>
                <img src="{{ Storage::url($modificationRequest->new_photo) }}" class="w-20 h-20 rounded-full object-cover">
            </div>
        @endif
        @if($modificationRequest->documents->isNotEmpty())
            <div class="mt-4">
                <p class="font-semibold text-gray-500 text-sm mb-2">Nouveaux documents</p>
                <ul class="space-y-1">
                    @foreach($modificationRequest->documents as $doc)
                        <li class="text-sm text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <a href="{{ Storage::url($doc->path) }}" target="_blank" class="text-primary-600 hover:underline">{{ $doc->original_name }}</a>
                            <span class="text-gray-400">({{ $doc->type }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

@endsection
