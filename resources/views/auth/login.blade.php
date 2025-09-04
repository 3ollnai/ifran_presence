@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="flex flex-col items-center bg-blue-200 p-6 rounded-lg">
    <h1 class="text-2xl font-bold text-[#23284F] mb-2">Connexion</h1>
    <p class="text-gray-600 mb-6 text-center">Connectez-vous pour accéder à votre compte</p>
    @if ($errors->any())
        <div class="mb-4">
            @foreach ($errors->all() as $error)
                <div class="text-red-600 text-sm text-center">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 text-red-600 text-sm text-center">
            {{ session('error') }}
        </div>
    @endif

    @if (session('status'))
        <div class="mb-4 text-green-600 text-sm text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ url('login') }}" class="w-full">
        @csrf
        <div class="mb-4">
            <input id="email" type="email" name="email" required autofocus
                placeholder="exemple@ifran.ci"
                class="w-full px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800 placeholder-gray-400">
        </div>
        <div class="mb-4">
            <input id="password" type="password" name="password" required
                placeholder="Mot de passe"
                class="w-full px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800 placeholder-gray-400">
        </div>
        <button type="submit"
            class="w-full bg-[#23284F] hover:bg-[#2b3266] text-white font-semibold py-3 rounded-full transition">
            Se connecter
        </button>
    </form>
    <div class="text-center mt-4">
        <span class="text-gray-500 text-sm">Pas de compte ?</span>
        <a href="{{ url('register') }}" class="text-[#23284F] text-sm hover:underline ml-1">S'inscrire</a>
    </div>
</div>
@endsection
