@extends('layouts.coordinateur')

@section('content')
    <div class="container mx-auto my-8">
        <h1 class="text-2xl font-bold mb-4">Justification des Absences</h1>

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

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-bold mb-4">Demandes d'Absence</h2>
            <div class="overflow-hidden">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-4 text-left font-medium text-gray-700 uppercase tracking-wider">Classe</th>
                            <th class="px-6 py-4 text-left font-medium text-gray-700 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-4 text-left font-medium text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left font-medium text-gray-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($demandes as $demande)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $demande->etudiant->classe->nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $demande->etudiant->user->nom }} {{ $demande->etudiant->user->prenom }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $demande->created_at->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $demande->justifie ? 'green' : 'yellow' }}-100 text-{{ $demande->justifie ? 'green' : 'yellow' }}-800">
                                        {{ $demande->justifie ? 'Justifiée' : 'En attente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if (!$demande->justifie)
                                        <form method="POST" action="{{ route('justifierAbsence', $demande->id) }}">
                                            @csrf
                                            <button type="submit" name="accepter"
                                                class="text-green-600 hover:text-green-900 mr-2">Accepter</button>
                                            <button type="submit" name="rejeter"
                                                class="text-red-600 hover:text-red-900">Rejeter</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
