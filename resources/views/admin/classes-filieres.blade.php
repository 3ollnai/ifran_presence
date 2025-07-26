@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Gestion des Classes et Filières</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Filières</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($filieres as $filiere)
                    <div class="bg-gray-100 p-4 rounded">
                        <h3 class="text-lg font-bold">{{ $filiere->nom }}</h3>
                        <p class="text-gray-600">{{ $filiere->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Classes</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Filière</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $classe)
                            <tr>
                                <td class="px-4 py-2">{{ $classe->nom }}</td>
                                <td class="px-4 py-2">{{ $classe->filiere->nom }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-end space-x-2">
                                        <button
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Modifier
                                        </button>
                                        <button
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Supprimer
                                        </button>
                                        <button
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="toggleStudentsList({{ $classe->id }})">
                                            Voir Étudiants
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="students-list-{{ $classe->id }}" style="display: none;">
                                <td colspan="4" class="px-4 py-2">
                                    <div class="bg-gray-100 p-4 rounded">
                                        <h5 class="font-bold">Étudiants dans cette classe :</h5>
                                        <ul class="list-disc pl-6">
                                            @forelse ($classe->etudiants as $etudiant)
                                                <li>{{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}</li>
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
