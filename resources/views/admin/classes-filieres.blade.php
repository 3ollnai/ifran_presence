@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-8 text-white">Gestion des Classes et Filières</h1>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold mb-6 text-gray-700">Filières</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($filieres as $filiere)
                    <div class="bg-gray-100 p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                        <h3 class="text-xl font-semibold text-blue-600">{{ $filiere->nom }}</h3>
                        <p class="text-gray-700 mt-2">{{ $filiere->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-3xl font-bold mb-6 text-gray-700">Classes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Nom</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Filière</th>
                            <th class="px-4 py-2 text-right text-gray-600 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $classe)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-2 border-b border-gray-300">{{ $classe->nom }}</td>
                                <td class="px-4 py-2 border-b border-gray-300">{{ $classe->filiere->nom ?? 'Aucune' }}</td>
                                <td class="px-4 py-2 border-b border-gray-300 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                            Modifier
                                        </button>
                                        <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                            Supprimer
                                        </button>
                                        <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out"
                                            onclick="toggleStudentsList({{ $classe->id }})">
                                            Voir Étudiants
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="students-list-{{ $classe->id }}" style="display: none;">
                                <td colspan="3" class="px-4 py-2">
                                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                        <h5 class="font-bold text-gray-800">Étudiants dans cette classe :</h5>
                                        <ul class="list-disc pl-6 mt-2">
                                            @forelse ($classe->etudiants as $etudiant)
                                                <li>{{ $etudiant->user->prenom ?? 'Inconnu' }} {{ $etudiant->user->nom ?? 'Inconnu' }}</li>
                                            @empty
                                                <li>Aucun étudiant inscrit dans cette classe.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<script>
    function toggleStudentsList(classId) {
        const studentsListRow = document.getElementById(`students-list-${classId}`);
        if (studentsListRow.style.display === 'none') {
            studentsListRow.style.display = 'table-row';
        } else {
            studentsListRow.style.display = 'none';
        }
    }
</script>
