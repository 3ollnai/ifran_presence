@extends('layouts.app')

@section('title', 'Tableau de Bord Administrateur')

@section('content')
    <div class="px-2 sm:px-4 md:px-8 py-4 max-w-7xl mx-auto w-full">
        <h1 class="text-2xl font-bold mb-2">Tableau de Bord Administrateur</h1>
        <h2 class="text-lg font-semibold text-gray-500 mb-6">Statistiques Clés</h2>
        {{-- Statistiques --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow flex flex-col">
                <span class="text-gray-500 font-medium">Total Étudiants</span>
                <span class="text-3xl font-bold text-violet-700 mt-2">520</span>
                <span class="text-xs text-green-600 mt-1 flex items-center gap-1">
                    +12 depuis le mois dernier
                </span>
            </div>
            <div class="bg-white rounded-xl p-5 shadow flex flex-col">
                <span class="text-gray-500 font-medium">Présents Aujourd'hui</span>
                <span class="text-3xl font-bold text-violet-700 mt-2">485</span>
                <span class="text-xs text-green-600 mt-1 flex items-center gap-1">
                    +2% par rapport à hier
                </span>
            </div>
            <div class="bg-white rounded-xl p-5 shadow flex flex-col">
                <span class="text-gray-500 font-medium">Absents Aujourd'hui</span>
                <span class="text-3xl font-bold text-violet-700 mt-2">35</span>
                <span class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    -1% par rapport à hier
                </span>
            </div>
            <div class="bg-white rounded-xl p-5 shadow flex flex-col">
                <span class="text-gray-500 font-medium">Absences justifiées</span>
                <span class="text-3xl font-bold text-violet-700 mt-2">20</span>
                <span class="text-xs text-green-600 mt-1 flex items-center gap-1">
                    +3 depuis la semaine dernière
                </span>
            </div>
        </div>
        {{-- Graphiques --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow min-h-[260px] flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold">Taux de Présence Mensuel</span>
                    <span class="text-xs text-gray-400"></span>
                </div>
                <div class="flex-1 flex items-center">
                    <canvas id="attendanceChart" height="120"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow min-h-[260px] flex flex-col">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold">Répartition des Absences</span>
                    <span class="text-xs text-gray-400"></span>
                </div>
                <div class="flex-1 flex items-center">
                    <canvas id="absencePieChart" height="120"></canvas>
                </div>
                <div class="flex flex-wrap gap-3 mt-4 text-xs">
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span> Médical
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span> Familial
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span> Retard
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Non justifié
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span> Autre
                    </div>
                </div>
            </div>
        </div>
        {{-- Notifications et évènements --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow">
                <span class="font-semibold block mb-2">Notifications Récentes</span>
                <ul class="space-y-2 text-sm">

                </ul>
            </div>
            <div class="bg-white rounded-xl p-5 shadow">
                <span class="font-semibold block mb-2">Événements et Cours à Venir</span>
                <ul class="space-y-2 text-sm">

                </ul>
            </div>
        </div>
        {{-- Activité récente et actions rapides --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-5 shadow">
                <span class="font-semibold block mb-2">Activité Récente du Système</span>
                <ul class="text-sm space-y-2">

                </ul>
            </div>
            <div class="bg-white rounded-xl p-5 shadow grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.users') }}"
                    class="bg-violet-50 hover:bg-violet-100 text-violet-700 font-semibold rounded-lg py-4 flex flex-col items-center justify-center shadow transition">
                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter un Utilisateur
                </a>
                <a href="{{ route('admin.classes-filieres') }}"
                    class="bg-violet-50 hover:bg-violet-100 text-violet-700 font-semibold rounded-lg py-4 flex flex-col items-center justify-center shadow transition">
                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Gérer les classes
                </a>
            </div>

        </div>
    </div>
@endsection

{{-- @section('scripts')
<script>
    // Graphique ligne : Taux de présence mensuel
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Taux de présence (%)',
                data: [95, 92, 96, 93, 97, 99, 98, 96, 97, 95, 98, 99],
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124,58,237,0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#7c3aed'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });

    // Graphique donut : Répartition des absences
    const pieCtx = document.getElementById('absencePieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Médical', 'Familial', 'Retard', 'Non justifié', 'Autre'],
            datasets: [{
                data: [12, 8, 5, 7, 3],
                backgroundColor: [
                    '#a78bfa', // violet
                    '#60a5fa', // bleu
                    '#fdba74', // orange
                    '#f87171', // rouge
                    '#a3a3a3'  // gris
                ],
                borderWidth: 2
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script> --}}
{{-- @endsection --}}
