@extends('layouts.coordinateur')

@section('content')
    <div class="container mx-auto py-8 px-2 sm:px-4">
        {{-- Messages de confirmation et d'erreur --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 font-semibold shadow">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 font-semibold shadow">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="font-semibold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Emploi du temps</h1>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <label for="module-select" class="mr-2 text-gray-600 font-medium">Module :</label>
                    <select id="module-select"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Tous les modules</option>
                        @foreach ($groupedSeances as $classe => $seances)
                            @foreach ($seances as $seance)
                                @if ($seance->module)
                                    <option value="{{ $seance->module->nom }}">{{ $seance->module->nom }}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center">
                    <label for="classe-select" class="mr-2 text-gray-600 font-medium">Classe :</label>
                    <select id="classe-select"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option value="">Toutes les classes</option>
                        @foreach ($groupedSeances as $classe => $seances)
                            <option value="{{ $classe }}">{{ $classe }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <label for="jour_debut" class="mr-2 text-gray-600 font-medium">Date début :</label>
                    <input type="text"
                        class="form-control flatpickr-input px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                        id="jour_debut" name="jour_debut" value="{{ $jourDebut }}" data-input>
                </div>
                <div class="flex items-center">
                    <label for="jour_fin" class="mr-2 text-gray-600 font-medium">Date fin :</label>
                    <input type="text"
                        class="form-control flatpickr-input px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                        id="jour_fin" name="jour_fin" value="{{ $jourFin }}" data-input>
                </div>
                <a href="{{ route('coordinateur.seances.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-violet-600 text-white rounded-lg shadow hover:bg-violet-700 transition font-semibold text-sm sm:text-base">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle séance
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($groupedSeances as $classe => $seances)
                @foreach ($seances as $seance)
                    <div class="bg-white rounded-lg shadow-md p-4" data-classe="{{ $classe }}"
                        data-date="{{ $seance->date }}" data-module="{{ $seance->module?->nom }}">
                        <h2 class="text-xl font-bold mb-4">{{ $classe }}</h2>
                        <h3 class="text-lg font-bold mb-2">{{ $seance->typeCours?->nom ?? '-' }}</h3>
                        <p class="text-gray-600 mb-2">
                            {{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            {{ $seance->heure_debut ? \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') : '-' }}
                            - {{ $seance->heure_fin ? \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') : '-' }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            @if ($seance->module)
                                Module : {{ $seance->module->nom }}
                            @else
                                Module : Non assigné
                            @endif
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                @if ($seance->professeur && $seance->professeur->user)
                                    <p class="text-gray-600 font-medium">
                                        {{ $seance->professeur->user->prenom ?? '' }}
                                        {{ $seance->professeur->user->nom ?? '' }}
                                    </p>
                                @else
                                    <p class="text-gray-400 italic">Non assigné</p>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    {{ $seance->presences()->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('coordinateur.seances.edit', $seance) }}"
                                class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-900 text-xs font-semibold shadow-sm"
                                title="Modifier">
                                Modifier
                            </a>
                            <button disabled
                                class="ml-2 px-2 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg opacity-50 cursor-not-allowed text-xs font-semibold shadow-sm"
                                title="Reporter bientôt disponible">
                                Reporter
                            </button>
                            <form action="{{ route('coordinateur.seances.destroy', $seance) }}" method="POST"
                                class="ml-2 inline"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette séance ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-2 py-1 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-900 text-xs font-semibold shadow-sm"
                                    title="Supprimer">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const moduleSelect = document.getElementById('module-select');
                const classeSelect = document.getElementById('classe-select');
                const jourDebutInput = document.getElementById('jour_debut');
                const jourFinInput = document.getElementById('jour_fin');
                const seanceElements = document.querySelectorAll('.bg-white[data-date]');

                moduleSelect.addEventListener('change', function() {
                    filterSeances();
                });

                classeSelect.addEventListener('change', function() {
                    filterSeances();
                });

                jourDebutInput.addEventListener('change', function() {
                    filterSeances();
                });

                jourFinInput.addEventListener('change', function() {
                    filterSeances();
                });

                function filterSeances() {
                    const selectedModule = moduleSelect.value;
                    const selectedClasse = classeSelect.value;
                    const jourDebutObj = jourDebutInput.value ? new Date(jourDebutInput.value) : null;
                    const jourFinObj = jourFinInput.value ? new Date(jourFinInput.value) : null;

                    seanceElements.forEach(function(element) {
                        const elementModule = element.dataset.module;
                        const elementClasse = element.dataset.classe;
                        const elementDate = element.dataset.date;
                        const elementDateObj = new Date(elementDate);

                        let shouldDisplay = true;

                        // Filtrer par module
                        if (selectedModule !== '' && (!elementModule || elementModule !== selectedModule)) {
                            shouldDisplay = false;
                        }

                        // Filtrer par classe
                        if (selectedClasse !== '' && elementClasse !== selectedClasse) {
                            shouldDisplay = false;
                        }

                        // Filtrer par date
                        if (jourDebutObj && jourFinObj) {
                            if (elementDateObj < jourDebutObj || elementDateObj > jourFinObj) {
                                shouldDisplay = false;
                            }
                        }

                        // Afficher ou cacher l'élément
                        element.style.display = shouldDisplay ? 'block' : 'none';
                    });
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script>
            flatpickr(".flatpickr-input", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
            });
        </script>
    </div>
@endsection
