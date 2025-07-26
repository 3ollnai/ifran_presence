@extends('layouts.coordinateur')

@section('title', 'Tableau de Bord - Coordinateur')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tableau de Bord du Coordinateur</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Nombre d'Étudiants</h3>
                    <p class="text-4xl font-bold text-violet-600">{{ $etudiants_count }}</p>
                </div>
                <svg class="w-12 h-12 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m0-8a4 4 0 114 4v8m0 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Nombre d'Enseignants</h3>
                    <p class="text-4xl font-bold text-violet-600">{{$professeur_count}}</p>
                </div>
                <svg class="w-12 h-12 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Nombre de Séances</h3>
                    <p class="text-4xl font-bold text-violet-600">{{$seances_count}}</p>
                </div>
                <svg class="w-12 h-12 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Nombre de Classes</h3>
                    <p class="text-4xl font-bold text-violet-600">{{$classe_count}}</p>
                </div>
                <svg class="w-12 h-12 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Présences par Classe</h2>
        <div class="bg-white rounded-lg shadow p-6">
            <canvas id="presencesChart"></canvas>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Présences par Séance</h2>
        <div class="bg-white rounded-lg shadow p-6">
            <canvas id="seancesChart"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script>
        // Données pour le graphique des présences par classe
        var classeLabels = [{{ implode(',', $classe_labels) }}];
        var classePresences = [{{ implode(',', $classe_presences) }}];

        // Données pour le graphique des présences par séance
        var seanceLabels = [{{ implode(',', $seance_labels) }}];
        var seancePresences = [{{ implode(',', $seance_presences) }}];

        // Initialisation du graphique des présences par classe
        var ctx = document.getElementById('presencesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: classeLabels,
                datasets: [{
                    label: 'Présences',
                    data: classePresences,
                    backgroundColor: '#7c3aed',
                    borderColor: '#7c3aed',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Initialisation du graphique des présences par séance
        var ctx2 = document.getElementById('seancesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: seanceLabels,
                datasets: [{
                    label: 'Présences',
                    data: seancePresences,
                    backgroundColor: '#7c3aed',
                    borderColor: '#7c3aed',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script> --}}
@endsection
