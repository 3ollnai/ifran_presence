@extends('layouts.professeur')

@section('title', 'Tableau de Bord du Professeur')

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Mes Séances</h2>

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <strong class="font-bold">Succès !</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left font-medium">Date</th>
                            <th class="px-4 py-2 text-left font-medium">Module</th>
                            <th class="px-4 py-2 text-left font-medium">Classe</th>
                            <th class="px-4 py-2 text-left font-medium">Étudiants</th>
                            <th class="px-4 py-2 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seancesWithStudentCount as $seance)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($seance['date'])->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $seance['module'] }}</td>
                                <td class="px-4 py-3">{{ $seance['classe'] }}</td>
                                <td class="px-4 py-3">{{ $seance['student_count'] }}</td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('professeur.showSeance', $seance['id']) }}" class="inline-block bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 transition">
                                        Marquer les présences/absences
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
