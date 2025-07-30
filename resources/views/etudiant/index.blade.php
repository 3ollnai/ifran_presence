@extends('layouts.etudiant')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tableau de Bord Étudiant</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Informations Personnelles</h2>
        <p>Nom : {{ $informationsPersonnelles['nom'] }}</p>
        <p>Classe : {{ $informationsPersonnelles['classe'] }}</p>
        <p>Numéro Étudiant : {{ $informationsPersonnelles['numeroEtudiant'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Prochains Cours</h2>
        <ul class="space-y-2">
            @foreach ($prochainsCours as $cours)
            <li>
                <h3 class="font-semibold">{{ $cours->module->name }}</h3>
                <p class="text-gray-500">{{ $cours->date->format('H:i') }} - {{ $cours->date->addHours(2)->format('H:i') }}, Salle {{ $cours->salle }}, Professeur : {{ $cours->professeur->name }}</p>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Actions Rapides</h2>
        <ul class="space-y-2">
            <li><a href="{{ route('etudiant.justifier_absence') }}" class="text-violet-600 hover:text-violet-800 transition">Justifier une absence</a></li>
            <li><a href="{{ route('etudiant.historique') }}" class="text-violet-600 hover:text-violet-800 transition">Revoir l'historique</a></li>
        </ul>
    </div>
</div>

<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h2 class="text-lg font-bold mb-4">Notifications Importantes</h2>
    <ul class="space-y-2">
        @foreach ($notificationsImportantes as $notification)
        <li>{{ $notification }}</li>
        @endforeach
    </ul>
</div>
@endsection
