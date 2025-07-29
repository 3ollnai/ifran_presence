@extends('layouts.coordinateur')

@section('content')
<div class="container mx-auto my-8">
    <h1 class="text-2xl font-bold mb-4">Justification des Absences Coordinateur</h1>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-bold mb-2">Tableau de Bord</h2>
            <div class="space-y-2">
                <div>
                    <span class="font-semibold">Demandes en attente:</span>
                    <span class="text-gray-600">{{ $demandes_en_attente }}</span>
                </div>
                <div>
                    <span class="font-semibold">Demandes approuvées:</span>
                    <span class="text-gray-600">{{ $demandes_approuvees }}</span>
                </div>
                <div>
                    <span class="font-semibold">Total des demandes:</span>
                    <span class="text-gray-600">{{ $total_demandes }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-bold mb-4">Demandes d'Absence</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Étudiant</th>
                        <th class="px-4 py-2 text-left">Classe</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Motif</th>
                        <th class="px-4 py-2 text-left">Statut</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($demandes as $demande)
                    <tr>
                        <td class="px-4 py-2">{{ $demande->etudiant->nom }} {{ $demande->etudiant->prenom }}</td>
                        <td class="px-4 py-2">{{ $demande->etudiant->classe->nom }}</td>
                        <td class="px-4 py-2">{{ $demande->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">{{ $demande->motif }}</td>
                        <td class="px-4 py-2">{{ $demande->justifie ? 'Justifiée' : 'En attente' }}</td>
                        <td class="px-4 py-2">
                            @if (!$demande->justifie)
                            <a href="{{ route('coordinateur.justifier-absence', $demande->id) }}" class="text-blue-500 hover:text-blue-700">Justifier</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <span>Sélectionnez une demande pour voir les détails.</span>
        </div>
    </div>
</div>
@endsection
