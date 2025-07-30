@extends('layouts.professeur')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Détails de la Séance</h1>

    <h2 class="text-xl font-medium mb-4 text-gray-800">Séance : {{ $seance->module->nom }}</h2>

    @if ($seance->classe)
        <h2 class="text-xl font-medium mb-4 text-gray-800">Classe : {{ $seance->classe->nom }}</h2>
    @else
        <h2 class="text-xl font-medium mb-4 text-gray-800">Classe : Non assignée</h2>
    @endif

    <h2 class="text-xl font-medium mb-4 text-gray-800">Liste des Étudiants</h2>

    <div class="overflow-x-auto">
        <form action="{{ route('professeur.markPresence', $seance->id) }}" method="POST">
            @csrf
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-indigo-100">
                        <th class="px-4 py-2 text-left text-indigo-600">Nom</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Présence</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($seance->classe && $seance->classe->etudiants && $seance->classe->etudiants->isNotEmpty())
                        @foreach ($seance->classe->etudiants as $etudiant)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 border border-gray-300">{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Présent" id="present-{{ $etudiant->id }}" class="form-radio h-4 w-4 text-green-600" {{ $etudiant->presence && $etudiant->presence->statuts->first()->statut == 'Présent' ? 'checked' : '' }}>
                                            <label for="present-{{ $etudiant->id }}" class="ml-2 text-green-600 cursor-pointer">Présent</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Retard" id="retard-{{ $etudiant->id }}" class="form-radio h-4 w-4 text-orange-600" {{ $etudiant->presence && $etudiant->presence->statuts->first()->statut == 'Retard' ? 'checked' : '' }}>
                                            <label for="retard-{{ $etudiant->id }}" class="ml-2 text-orange-600 cursor-pointer">Retard</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Absent" id="absent-{{ $etudiant->id }}" class="form-radio h-4 w-4 text-red-600" {{ $etudiant->presence && $etudiant->presence->statuts->first()->statut == 'Absent' ? 'checked' : '' }}>
                                            <label for="absent-{{ $etudiant->id }}" class="ml-2 text-red-600 cursor-pointer">Absent</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" class="px-4 py-2 border border-gray-300 text-center">Aucun étudiant trouvé pour cette séance.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded transition duration-200">
                    Enregistrer les Présences
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
