@extends('layouts.admin')

@section('title', 'Nouvel agent municipal')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer un agent municipal</h1>
    <p class="text-gray-600 mb-8">Le compte sera actif et l’adresse email marquée comme vérifiée.</p>

    <div class="max-w-lg bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-800">
                @foreach($errors->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.agents.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Commune</label>
                <select name="commune_id" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @foreach($communes as $c)
                        <option value="{{ $c->id }}" @selected(old('commune_id', $defaultCommuneId) == $c->id)>
                            {{ $c->name }}@if($c->department_name) — {{ $c->department_name }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe initial</label>
                <input type="password" name="password" required autocomplete="new-password"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-colors">
                    Créer l’agent
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">
                    Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
