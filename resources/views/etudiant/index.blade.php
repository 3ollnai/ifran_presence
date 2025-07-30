@extends('layouts.etudiant')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tableau de Bord Étudiant</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Informations Personnelles</h2>
        <p>Nom : {{ $informationsPersonnelles['nom'] }}</p>
        <p>Classe : {{ $informationsPersonnelles['classe'] }}</p>
        <p>Numéro Étudiant : {{ $informationsPersonnelles['numeroEtudiant'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Taux de Présence</h2>
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
                            text: '{{ round($tauxPresence, 2) }}%'
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
        <p class="{{ $tauxPresenceColor }} mt-4">{{ $tauxPresenceMessage }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Actions Rapides</h2>
        <ul class="space-y-2">
            <li><a href="{{ route('etudiant.justifier_absence') }}" class="text-violet-600 hover:text-violet-800 transition">Justifier une absence</a></li>
            <li><a href="{{ route('etudiant.historique') }}" class="text-violet-600 hover:text-violet-800 transition">Revoir l'historique</a></li>
        </ul>
    </div>
</div>

<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h2 class="text-lg font-bold mb-4">Notifications Importantes</h2>
    <ul class="space-y-2">
        @foreach ($notificationsImportantes as $notification)
        <li>{{ $notification }}</li>
        @endforeach
    </ul>
</div>
@endsection
