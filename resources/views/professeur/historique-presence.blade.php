@extends('layouts.professeur')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Historique des Présences</h1>

    @if (count($absencesNonJustifiees) > 0)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <strong class="font-bold">Absences Non Justifiées ({{ count($absencesNonJustifiees) }})</strong>
        <span class="block sm:inline">{{ $absencesNonJustifiees[0]->message }}</span>
    </div>
    @endif

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div>
            <h2 class="text-xl font-medium mb-2 text-gray-800">Total Classes Enseignées</h2>
            <p class="text-4xl font-bold text-indigo-600">{{ $totalClassesEnseignees }}</p>
        </div>
        <div>
            <h2 class="text-xl font-medium mb-2 text-gray-800">Classes Ce Mois</h2>
            <p class="text-4xl font-bold text-indigo-600">{{ $classesCurrentMois }}</p>
        </div>
        <div>
            <h2 class="text-xl font-medium mb-2 text-gray-800">Taux de Présence Moyen</h2>
            <p class="text-4xl font-bold text-indigo-600">{{ $tauxPresenceMoyen }}%</p>
        </div>
        <div>
            <h2 class="text-xl font-medium mb-2 text-gray-800">Étudiants Total</h2>
            <p class="text-4xl font-bold text-indigo-600">{{ $etudiantsTotal }}</p>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-medium mb-4 text-gray-800">Taux de Présence Moyen</h2>
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                <canvas id="presenceChart"></canvas>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-medium mb-4 text-gray-800">Nombre d'étudiants par séance</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-indigo-100">
                        <th class="px-4 py-2 text-left text-indigo-600">Date</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Nombre d'étudiants</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($seances as $seance)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ $seanceEtudiantCount[$seance->id] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-medium mb-4 text-gray-800">Absences Récentes des Étudiants</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-indigo-100">
                        <th class="px-4 py-2 text-left text-indigo-600">Nom de l'étudiant</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Date</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Cours</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Statut</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Justification</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absencesRecentes as $absence)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border border-gray-300">{{ $absence->etudiant->user->nom }}
                            {{ $absence->etudiant->user->prenom }}</td>
                        <td class="px-4 py-2 border border-gray-300">
                            {{ \Carbon\Carbon::parse($absence->created_at)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ $absence->seance->module->nom }}</td>
                        <td class="px-4 py-2 border border-gray-300 @if ($absence->statut->statut == 'Présent') text-green-500 @elseif ($absence->statut->statut == 'En retard') text-orange-500 @else text-red-500 @endif">{{ $absence->statut->statut }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ $absence->justification ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('presenceChart').getContext('2d');
    var presenceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Taux de Présence Moyen'],
            datasets: [{
                label: 'Taux de Présence',
                data: [{{ $tauxPresenceMoyen }}],
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value, index, ticks) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
