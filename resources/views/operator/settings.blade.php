@extends('layouts.app')

@section('title', 'Paramètres - CommunePro')

@section('content')
<div class="bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 rounded-2xl border border-gray-100">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Paramètres</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base max-w-2xl">
                    Gérez les informations de votre compte, votre mot de passe et vos préférences de newsletter.
                </p>
            </div>
            <a href="{{ route('operator.profile.show') }}"
               class="inline-flex items-center justify-center text-sm font-semibold text-primary-600 hover:text-primary-700 shrink-0">
                Mon profil
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:items-stretch">

            {{-- Informations du compte --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 min-w-0 flex flex-col h-full">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6">Informations du compte</h2>
                <form method="POST" action="{{ route('operator.settings.account') }}" class="space-y-4">
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
                            class="inline-flex items-center justify-center bg-primary-600 text-white px-6 py-2.5 rounded-xl hover:bg-primary-700 font-semibold text-sm transition-all shadow-sm hover:shadow">
                        Enregistrer
                    </button>
                </form>
            </div>

            {{-- Changer le mot de passe --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 min-w-0 flex flex-col h-full">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6">Changer le mot de passe</h2>
                <form method="POST" action="{{ route('operator.settings.password') }}" class="space-y-4">
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
                            class="inline-flex items-center justify-center bg-primary-600 text-white px-6 py-2.5 rounded-xl hover:bg-primary-700 font-semibold text-sm transition-all shadow-sm hover:shadow">
                        Changer le mot de passe
                    </button>
                </form>
            </div>

            {{-- Newsletter --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 min-w-0 flex flex-col h-full">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Newsletter</h2>
                <p class="text-sm text-gray-600 mb-6">Recevez nos actualités et nouveautés directement par email.</p>
                <form method="POST" action="{{ route('operator.settings.newsletter') }}">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center justify-between py-4 border-b border-gray-100">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Abonnement newsletter</p>
                            <p class="text-xs text-gray-500 mt-0.5">Recevoir les actualités de la communauté par email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="newsletter_subscribed" value="1"
                                   {{ ($newsletter && $newsletter->subscribed) ? 'checked' : '' }}
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

        </div>
    </div>
</div>
@endsection
