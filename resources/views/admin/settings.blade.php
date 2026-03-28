@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Paramètres</h1>

    <div class="space-y-6">

        {{-- Informations du compte --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Informations du compte</h2>
            <form method="POST" action="{{ route('admin.settings.account') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all
                                  @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all
                                  @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="bg-primary-600 text-white px-8 py-3 rounded-xl hover:bg-primary-700 font-semibold transition-all shadow-lg hover:shadow-xl">
                    Enregistrer
                </button>
            </form>
        </div>

        {{-- Changer le mot de passe --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Changer le mot de passe</h2>
            <form method="POST" action="{{ route('admin.settings.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all
                                  @error('current_password') border-red-400 @enderror">
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all
                                  @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>
                <button type="submit"
                        class="bg-primary-600 text-white px-8 py-3 rounded-xl hover:bg-primary-700 font-semibold transition-all shadow-lg hover:shadow-xl">
                    Changer le mot de passe
                </button>
            </form>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Notifications par email</h2>
            <p class="text-sm text-gray-600 mb-6">Choisissez les emails que vous souhaitez recevoir.</p>
            <form method="POST" action="{{ route('admin.settings.notifications') }}">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between py-4 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Nouveau profil soumis</p>
                        <p class="text-xs text-gray-500 mt-0.5">Recevoir un email quand un opérateur soumet un profil</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notify_new_profile" value="1"
                               {{ $user->notify_new_profile ? 'checked' : '' }}
                               class="sr-only peer"
                               onchange="this.form.submit()">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border-gray-300 after:border after:rounded-full
                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </form>
        </div>
        @endif

    </div>
@endsection
