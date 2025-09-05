@extends('layouts.professeur')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-8 mt-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Détails de la Séance</h1>

    <div class="grid grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-medium mb-4 text-gray-800">Séance : {{ $seance->module->nom }}</h2>
            @if ($seance->classe)
                <h2 class="text-xl font-medium mb-4 text-gray-800">Classe : {{ $seance->classe->nom }}</h2>
            @else
                <h2 class="text-xl font-medium mb-4 text-gray-800">Classe : Non assignée</h2>
            @endif
        </div>
        <div class="flex justify-end">
            <form action="{{ route('professeur.markPresence', $seance->id) }}" method="POST" class="w-full max-w-md">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 w-full">
                    Enregistrer les Présences
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto mt-8">
        <table class="w-full table-auto border-collapse border border-gray-300 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-indigo-600 text-white">
                    <th class="px-4 py-3 text-left font-medium">Nom</th>
                    <th class="px-4 py-3 text-left font-medium">Présence</th>
                </tr>
            </thead>
            <tbody>
                @if ($seance->classe && $seance->classe->etudiants && $seance->classe->etudiants->isNotEmpty())
                    @foreach ($seance->classe->etudiants as $etudiant)
                        <tr class="hover:bg-gray-100 transition-colors duration-200">
                            <td class="px-4 py-3 border-b border-gray-200 font-medium">{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}</td>
                            <td class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Présent" id="present-{{ $etudiant->id }}" class="form-radio h-5 w-5 text-green-500 focus:ring-green-500 focus:ring-2 focus:outline-none" {{ $etudiant->presence && $etudiant->presence->statuts->first()->statut == 'Présent' ? 'checked' : '' }}>
                                        <label for="present-{{ $etudiant->id }}" class="ml-2 text-green-500 font-medium cursor-pointer">Présent</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Retard" id="retard-{{ $etudiant->id }}" class="form-radio h-5 w-5 text-orange-500 focus:ring-orange-500 focus:ring-2 focus:outline-none" {{ $etudiant->presence && $etudiant->presence->statuts->first()->statut == 'Retard' ? 'checked' : '' }}>
                                        <label for="retard-{{ $etudiant->id }}" class="ml-2 text-orange-500 font-medium cursor-pointer">Retard</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Absent" id="absent-{{ $etudiant->id }}" class="form-radio h-5 w-5 text-red-500 focus:ring-red-500 focus:ring-2 focus:outline-none" {{ $etudiant->presence && $etudiant->presence->statuts->first()->statut == 'Absent' ? 'checked' : '' }}>
                                        <label for="absent-{{ $etudiant->id }}" class="ml-2 text-red-500 font-medium cursor-pointer">Absent</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="px-4 py-3 border-b border-gray-200 text-center font-medium">Aucun étudiant trouvé pour cette séance.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
