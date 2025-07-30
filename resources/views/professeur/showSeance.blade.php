@extends('layouts.professeur')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Saisie des Présences</h1>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <strong class="font-bold">Erreur !</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <form action="{{ route('professeur.markPresence', $seance->id) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="class" class="block font-medium text-gray-700 mb-2">Classe</label>
            <select id="class" class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" disabled>
                <option value="{{ $seance->classe->id }}">{{ $seance->classe->nom }}</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="date" class="block font-medium text-gray-700 mb-2">Date</label>
            <input type="text" id="date" class="block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ \Carbon\Carbon::parse($seance->date)->format('Y-m-d') }}" disabled>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-medium mb-4 text-gray-800">Liste des Étudiants</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-indigo-100">
                            <th class="px-4 py-2 text-left text-indigo-600">Nom</th>
                            <th class="px-4 py-2 text-left text-indigo-600">Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seance->classe->etudiants as $etudiant)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">{{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}</td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Présent" id="present-{{ $etudiant->id }}" class="form-radio h-4 w-4 text-green-600" {{ $etudiant->presence && $etudiant->presence->statut->statut == 'Présent' ? 'checked' : '' }}>
                                        <label for="present-{{ $etudiant->id }}" class="ml-2 text-green-600 cursor-pointer">Présent</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Retard" id="retard-{{ $etudiant->id }}" class="form-radio h-4 w-4 text-orange-600" {{ $etudiant->presence && $etudiant->presence->statut->statut == 'Retard' ? 'checked' : '' }}>
                                        <label for="retard-{{ $etudiant->id }}" class="ml-2 text-orange-600 cursor-pointer">Retard</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="etudiants[{{ $etudiant->id }}]" value="Absent" id="absent-{{ $etudiant->id }}" class="form-radio h-4 w-4 text-red-600" {{ $etudiant->presence && $etudiant->presence->statut->statut == 'Absent' ? 'checked' : '' }}>
                                        <label for="absent-{{ $etudiant->id }}" class="ml-2 text-red-600 cursor-pointer">Absent</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded transition duration-200">
                Enregistrer les Présences
            </button>
        </div>
    </form>
</div>
@endsection
