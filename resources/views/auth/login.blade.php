@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
    <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
    @if ($errors->any())
        <div class="mb-4">
            @foreach ($errors->all() as $error)
                <div class="text-red-600 text-sm">{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ url('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-medium" for="email">Email</label>
            <input id="email" type="email" name="email" required autofocus
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"/>
        </div>
        <div class="mb-6">
            <label class="block mb-1 font-medium" for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required
                class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"/>
        </div>
        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Se connecter</button>
    </form>
    <div class="mt-4 text-center">
        <a href="{{ url('register') }}" class="text-blue-600 hover:underline">Créer un compte</a>
    </div>
@endsection
