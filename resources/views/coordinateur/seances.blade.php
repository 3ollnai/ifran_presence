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
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Gestion de l'Emploi du Temps</h1>
            <a href="{{ route('coordinateur.seances.create') }}"
                class="inline-flex items-center px-4 py-2 bg-violet-600 text-white rounded-lg shadow hover:bg-violet-700 transition font-semibold text-sm sm:text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle séance
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full bg-white text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-violet-100 via-blue-100 to-purple-100">
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase">Date</th>
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase">Heure début</th>
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase">Heure fin</th>
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase">Type</th>
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase">Classe</th>
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase hidden md:table-cell">Module</th>
                        <th class="px-2 py-3 text-left font-bold text-gray-600 uppercase hidden lg:table-cell">Professeur</th>
                        <th class="px-2 py-3 text-center font-bold text-gray-600 uppercase hidden xl:table-cell">Présences</th>
                        <th class="px-2 py-3 text-center font-bold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seances as $seance)
                        <tr class="@if ($loop->odd) bg-gray-50 @endif hover:bg-violet-50 transition">
                            <td class="px-2 py-3 font-medium">
                                {{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-2 py-3">
                                {{ $seance->heure_debut ? \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') : '-' }}
                            </td>
                            <td class="px-2 py-3">
                                {{ $seance->heure_fin ? \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') : '-' }}
                            </td>
                            <td class="px-2 py-3">
                                {{ $seance->typeCours?->nom ?? '-' }}
                            </td>
                            <td class="px-2 py-3">
                                {{ $seance->classe?->nom ?? '-' }}
                            </td>
                            <td class="px-2 py-3 hidden md:table-cell">
                                @php
                                    $module = is_string($seance->module)
                                        ? json_decode($seance->module, true)
                                        : $seance->module;
                                @endphp
                                {{ $module['nom'] ?? '-' }}
                            </td>
                            <td class="px-2 py-3 hidden lg:table-cell">
                                @if ($seance->professeur && $seance->professeur->user)
                                    {{ $seance->professeur->user->prenom ?? '' }}
                                    {{ $seance->professeur->user->nom ?? '' }}
                                @else
                                    <span class="italic text-gray-400">Non assigné</span>
                                @endif
                            </td>
                            <td class="px-2 py-3 text-center hidden xl:table-cell">
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    {{ $seance->presences()->count() }}
                                </span>
                            </td>
                            <td class="px-2 py-3 text-center">
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-1">
                                    <a href="{{ route('coordinateur.seances.edit', $seance) }}"
                                        class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-900 text-xs font-semibold shadow-sm"
                                        title="Modifier">
                                        Modifier
                                    </a>
                                    <button disabled
                                        class="px-2 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg opacity-50 cursor-not-allowed text-xs font-semibold shadow-sm"
                                        title="Reporter bientôt disponible">
                                        Reporter
                                    </button>
                                    <form action="{{ route('coordinateur.seances.destroy', $seance) }}" method="POST"
                                        class="inline"
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-400 text-lg">Aucune séance trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $seances->links() }}
        </div>
    </div>
@endsection
