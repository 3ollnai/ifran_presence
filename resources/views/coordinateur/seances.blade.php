@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Liste des Séances</h1>

    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2 border">Date</th>
                <th class="px-4 py-2 border">Classe</th>
                <th class="px-4 py-2 border">Professeur</th>
                <th class="px-4 py-2 border">Présences</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($seances as $seance)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $seance->date }}</td>
                    <td class="px-4 py-3">{{ $seance->classe ? $seance->classe->nom : '-' }}</td>
                    <td class="px-4 py-3">
                        @if($seance->professeur)
                            {{ $seance->professeur->nom . ' ' . $seance->professeur->prenom }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $seance->presences()->count() }}</td>
                    <td class="px-4 py-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('coordinateur.seances.edit', $seance) }}" class="text-violet-600 hover:text-violet-800">
                                Modifier
                            </a>
                            <a href="{{ route('coordinateur.seances.reporter', $seance) }}" class="text-blue-600 hover:text-blue-800">
                                Reporter
                            </a>
                            <form action="{{ route('coordinateur.seances.destroy', $seance) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-3 text-center">Aucune séance trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
