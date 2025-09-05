@extends('layouts.professeur')

@section('title', 'Tableau de Bord du Professeur')

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-user-circle text-blue-500 mr-4 text-3xl"></i>
                <h1 class="text-2xl font-bold">Bonjour, <span class="text-blue-500">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span> !</h1>
            </div>
            <img src="{{ url('https://cdn.discordapp.com/attachments/1404249843878199408/1413567593805250671/image.png?ex=68bc66e9&is=68bb1569&hm=4b2aae9139a64fe61917700d1c483e94c202ae3c4d272f58597ec8950b9f51e0&') }}" alt="Profile Image" class="h-12 w-12 rounded-full">
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Mes Prochains Cours</h2>

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
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
                        @php
                            $currentMonth = Carbon\Carbon::now()->month;
                            $nextMonth = Carbon\Carbon::now()->addMonth()->month;
                        @endphp
                        @foreach ($seancesWithStudentCount as $seance)
                            @php
                                $seanceMonth = Carbon\Carbon::parse($seance['date'])->month;
                            @endphp
                            @if ($seanceMonth == $currentMonth || $seanceMonth == $nextMonth)
                            <tr class="border-b">
                                <td class="px-4 py-3">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($seance['date'])->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <i class="fas fa-book mr-2"></i>
                                    {{ $seance['module'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $seance['classe'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    {{ $seance['student_count'] }}
                                </td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('professeur.showSeance', $seance['id']) }}" class="inline-block bg-green-500 text-white font-semibold py-2 px-4 rounded hover:bg-green-600 transition">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Marquer les présences/absences
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

   <div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h2 class="text-lg font-semibold mb-4">
        <i class="fas fa-chart-line text-blue-500 mr-2"></i>
        Statistiques de présence
    </h2>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <h3 class="text-lg font-semibold mb-2">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                Absences justifiées
            </h3>
            <p>{{ count($absencesjustifiees) }}</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold mb-2">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                Taux de présence moyen
            </h3>
            <p>{{ number_format($tauxPresenceMoyen, 2) }}%</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold mb-2">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                Absences récentes
            </h3>
            <p>{{ count($absencesRecentes) }}</p>
        </div>
    </div>
</div>

    </div>
@endsection
