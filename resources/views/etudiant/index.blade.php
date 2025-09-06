@extends('layouts.etudiant')

@section('content')
<h1 class="text-5xl font-extrabold mb-10 text-center text-white">Tableau de Bord Étudiant</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <div class="bg-white rounded-lg shadow-lg p-8 transition-transform duration-300 ease-in-out">
        <h2 class="text-2xl font-semibold mb-6 text-blue-700 border-b pb-3">Informations Personnelles</h2>
        <p class="text-gray-700 text-lg">Nom : <span class="font-medium">{{ $informationsPersonnelles['nom'] }}</span></p>
        <p class="text-gray-700 text-lg">Prénom : <span class="font-medium">{{ $informationsPersonnelles['prenom'] }}</span></p>
        <p class="text-gray-700 text-lg">Classe : <span class="font-medium">{{ $informationsPersonnelles['classe'] }}</span></p>
        <p class="text-gray-700 text-lg">Numéro Étudiant : <span class="font-medium">{{ $informationsPersonnelles['numeroEtudiant'] }}</span></p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 transition-transform duration-300 ease-in-out">
        <h2 class="text-2xl font-semibold mb-6 text-blue-700 border-b pb-3">Taux de Présence</h2>
        <div class="flex justify-center">
            <canvas id="presenceRateChart" width="200" height="200"></canvas>
        </div>
        <script>
            var ctx = document.getElementById('presenceRateChart').getContext('2d');
            var presenceRateChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [{{ round($tauxPresence, 2) }}, 100 - {{ round($tauxPresence, 2) }}],
                        backgroundColor: ['{{ $tauxPresenceColor }}', '#e0e0e0']
                    }]
                },
                options: {
                    cutoutPercentage: 70,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ round($tauxPresence, 2) }}%',
                            font: {
                                size: 28,
                                weight: 'bold'
                            },
                            color: '{{ $tauxPresenceColor }}'
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
        <p class="{{ $tauxPresenceColor }} mt-4 font-semibold text-lg">{{ $tauxPresenceMessage }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 transition-transform duration-300 ease-in-out">
        <h2 class="text-2xl font-semibold mb-6 text-blue-700 border-b pb-3">Actions Rapides</h2>
        <div class="space-y-4">
            <a href="{{ route('etudiant.justifier_absence') }}" class="block bg-blue-600 text-white text-lg text-center py-3 rounded-lg shadow hover:bg-blue-700 transition duration-200">Justifier une absence</a>
            <a href="{{ route('etudiant.historique') }}" class="block bg-blue-600 text-white text-lg text-center py-3 rounded-lg shadow hover:bg-blue-700 transition duration-200">Revoir l'historique</a>
        </div>
    </div>
</div>

<div class="mt-8 bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-semibold mb-6 text-blue-700 border-b pb-3">Notifications Importantes</h2>
    <ul class="space-y-2">
        @foreach ($notificationsImportantes as $notification)
        <li class="text-gray-800 text-lg">- {{ $notification }}</li>
        @endforeach
    </ul>
</div>
@endsection
