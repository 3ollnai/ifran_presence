@extends('layouts.coordinateur')

@section('content')
    <div class="container mx-auto max-w-lg py-10">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-violet-700 mb-6">Modifier la séance</h1>

            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Oups !</strong>
                        <span class="block">Veuillez corriger les erreurs suivantes :</span>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('coordinateur.seances.update', $seance->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT') <!-- Indique que c'est une mise à jour -->

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">Date de la séance</label>
                    <input type="date" name="date" id="date"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-300"
                        value="{{ old('date', $seance->date) }}" required>
                </div>

                <!-- Classe -->
                <div>
                    <label for="classe_id" class="block text-sm font-semibold text-gray-700 mb-1">Classe</label>
                    <select name="classe_id" id="classe_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-300"
                        required>
                        <option value="">Sélectionner une classe</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}" {{ $seance->classe_id == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Module -->
                <div>
                    <label for="module_id" class="block text-sm font-semibold text-gray-700 mb-1">Module</label>
                    <select name="module_id" id="module_id"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-300"
                        required>
                        <option value="">Sélectionner un module</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module->id }}" {{ $seance->module_id == $module->id ? 'selected' : '' }}>
                                {{ $module->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Professeur -->
                <div class="mb-4">
                    <label for="professeur_id" class="block text-gray-700 font-semibold mb-2">
                        Professeur
                    </label>
                    <select name="professeur_id" id="professeur_id"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                        required>
                        <option value="">Sélectionner un professeur</option>
                        @foreach ($professeurs as $professeur)
                            <option value="{{ $professeur->id }}" {{ old('professeur_id', $seance->professeur_id) == $professeur->id ? 'selected' : '' }}>
                                {{ $professeur->user->prenom }} {{ $professeur->user->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('professeur_id')
                        <div class="text-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Heure début -->
                <div>
                    <label for="heure_debut" class="block text-sm font-semibold text-gray-700 mb-1">Heure de début</label>
                    <input type="time" name="heure_debut" id="heure_debut"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-300"
                        value="{{ old('heure_debut', $seance->heure_debut) }}" required>
                </div>

                <!-- Heure fin -->
                <div>
                    <label for="heure_fin" class="block text-sm font-semibold text-gray-700 mb-1">Heure de fin</label>
                    <input type="time" name="heure_fin" id="heure_fin"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-300"
                        value="{{ old('heure_fin', $seance->heure_fin) }}" required>
                </div>

                <!-- Type de séance -->
                <div class="mb-4">
                    <label for="type_cours_id" class="block font-semibold mb-1">Type de cours</label>
                    <select name="type_cours_id" id="type_cours_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Sélectionner un type</option>
                        @foreach ($typesCours as $type)
                            <option value="{{ $type->id }}" {{ $seance->type_cours_id == $type->id ? 'selected' : '' }}>
                                {{ $type->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('coordinateur.seances') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-violet-600 text-white rounded-lg font-semibold shadow hover:bg-violet-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
