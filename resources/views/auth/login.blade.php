@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="min-h-screen flex flex-col justify-between bg-white">
    <div class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-md mx-auto">
            <div class="flex flex-col items-center mb-8">
                <h1 class="text-3xl font-bold text-[#23284F] tracking-wide text-center">IFRAN</h1>
                <span class="text-[#23284F] text-base tracking-widest -mt-2 mb-2">EDUQUO</span>
            </div>

            {{-- AFFICHAGE DES MESSAGES D'ERREUR --}}
            @if ($errors->any())
                <div class="mb-4">
                    @foreach ($errors->all() as $error)
                        <div class="text-red-600 text-sm text-center">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- AFFICHAGE DES MESSAGES DE SESSION --}}
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

            <form method="POST" action="{{ url('login') }}" class="bg-white p-8 rounded shadow-none">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input id="email" type="email" name="email" required autofocus
                        placeholder="e.g. username@ifran.ci"
                        class="w-full px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800 placeholder-gray-400"/>
                </div>
                <div class="mb-2">
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                        placeholder="enter your password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-300 text-gray-800 placeholder-gray-400"/>
                </div>
                <button type="submit"
                    class="w-full bg-purple-400 hover:bg-purple-500 text-white font-semibold py-3 rounded-full transition mb-3">
                    Se connecter
                </button>
            </form>
            <div class="text-center mt-4">
                <span class="text-gray-500 text-sm">Vous n'avez pas de compte ?</span>
                <a href="{{ url('register') }}" class="text-purple-500 text-sm hover:underline ml-1">Inscrivez-vous dès maintenant</a>
            </div>
        </div>
    </div>
</div>
@endsection
