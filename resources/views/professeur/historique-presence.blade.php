@extends('layouts.professeur')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-indigo-700">Historique des Présences</h1>

    @if (count($absencesRecentes) > 0)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <strong class="font-bold">Absences Non Justifiées ({{ count($absencesRecentes) }})</strong>
    </div>
    @else
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <strong class="font-bold">Aucune absence non justifiée ce mois-ci.</strong>
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
            <p class="text-4xl font-bold text-indigo-600">{{ number_format($tauxPresenceMoyen, 2) }}%</p>
        </div>
        <div>
            <h2 class="text-xl font-medium mb-2 text-gray-800">Étudiants Total</h2>
            <p class="text-4xl font-bold text-indigo-600">{{ $etudiantsTotal }}</p>
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
                        <td class="px-4 py-2 border border-gray-300">{{ $seanceEtudiantCount[$seance->id] ?? 0 }}</td>
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
                    @if (count($absencesRecentes) > 0)
                        @foreach ($absencesRecentes as $absence)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">{{ $absence->etudiant->user->nom }} {{ $absence->etudiant->user->prenom }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($absence->created_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ $absence->seance->module->nom }}</td>
                            <td class="px-4 py-2 border border-gray-300 @if ($absence->statut->statut == 'Présent') text-green-500 @elseif ($absence->statut->statut == 'En retard') text-orange-500 @else text-red-500 @endif">{{ $absence->statut->statut }}</td>
                            <td class="px-4 py-2 border border-gray-300">
                                @if ($absence->justification)
                                    Justifié
                                @else
                                    En attente de justification
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-4 py-2 border border-gray-300 text-center">Aucune absence récente enregistrée.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
<br>

    <div>
        <h2 class="text-xl font-medium mb-4 text-gray-800">Absences Justifiées</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-indigo-100">
                        <th class="px-4 py-2 text-left text-indigo-600">Nom de l'étudiant</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Date</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Cours</th>
                        <th class="px-4 py-2 text-left text-indigo-600">Justification</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($absencesJustifiees) > 0)
                        @foreach ($absencesJustifiees as $absence)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">{{ $absence->etudiant->user->nom }} {{ $absence->etudiant->user->prenom }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($absence->created_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ $absence->seance->module->nom }}</td>
                            <td class="px-4 py-2 border border-gray-300">Justifié</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-2 border border-gray-300 text-center">Aucune absence justifiée enregistrée.</td>
                        </tr>
                    @endif
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
            labels: {!! json_encode($presenceLabels) !!}, // Utilisation des labels pour le graphique
            datasets: [{
                label: 'Taux de Présence',
                data: {!! json_encode($presenceData) !!}, // Utilisation des données pour le graphique
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
                        callback: function(value) {
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
