@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg mt-10">
    <h2 class="text-3xl font-bold mb-6 text-center text-green-700">Inscription</h2>
    @if ($errors->any())
        <div class="mb-4">
            @foreach ($errors->all() as $error)
                <div class="bg-red-100 text-red-700 px-3 py-2 rounded mb-2 text-sm">{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ url('register') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700" for="nom">Nom</label>
            <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 transition"/>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700" for="prenom">Prénom</label>
            <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 transition"/>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 transition"/>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700" for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 transition"/>
        </div>
        <div class="mb-6">
            <label class="block mb-1 font-medium text-gray-700" for="password_confirmation">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 transition"/>
        </div>
        <button type="submit"
            class="w-full bg-green-600 text-white font-semibold py-2 rounded hover:bg-green-700 transition">S'inscrire</button>
    </form>
    <div class="mt-6 text-center">
        <a href="{{ url('login') }}" class="text-green-700 hover:underline">Déjà un compte ? Se connecter</a>
    </div>
</div>
@endsection
