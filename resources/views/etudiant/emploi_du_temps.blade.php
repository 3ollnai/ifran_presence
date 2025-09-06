@extends('layouts.etudiant')

@section('title', 'Emploi du Temps')

@section('content')
<h1 class="text-4xl font-extrabold mb-10 text-center text-white shadow-lg p-4 bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg">Emploi du Temps</h1>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
        <thead>
            <tr class="bg-gray-100 text-gray-800">
                <th class="py-4 px-6 text-left text-lg font-semibold border-b border-gray-300 rounded-tl-lg rounded-tr-lg">Date</th>
                <th class="py-4 px-6 text-left text-lg font-semibold border-b border-gray-300">Heure</th>
                <th class="py-4 px-6 text-left text-lg font-semibold border-b border-gray-300 rounded-tr-lg rounded-tl-lg">Module</th>
            </tr>
        </thead>
        <tbody>
            @if($seances->isEmpty())
                <tr>
                    <td colspan="3" class="py-4 px-6 text-center text-gray-500 italic">Aucune séance prévue.</td>
                </tr>
            @else
                @foreach($seances as $seance)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-300">
                        <td class="py-4 px-6 text-sm text-gray-800 font-medium">{{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}</td>
                        <td class="py-4 px-6 text-sm text-gray-800 font-medium">{{ $seance->heure_debut }} - {{ $seance->heure_fin }}</td>
                        <td class="py-4 px-6 text-sm text-gray-800 font-medium">{{ $seance->module->nom }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@endsection
