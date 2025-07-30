@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="max-w-lg mx-auto mt-14 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-7 flex items-center gap-2">
        <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Modifier un utilisateur
    </h1>

    @if ($errors->any())
        <div class="mb-6 flex items-center gap-3 text-red-700 bg-red-100 border border-red-200 p-4 rounded-lg shadow">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <ul class="ml-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }} class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700">Nom</label>
            <input type="text" name="nom"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                required value="{{ old('nom', $user->nom) }}" placeholder="Nom">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700">Prénom</label>
            <input type="text" name="prenom"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                required value="{{ old('prenom', $user->prenom) }}" placeholder="Prénom">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700">Email</label>
            <input type="email" name="email"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                required value="{{ old('email', $user->email) }}" placeholder="exemple@domaine.com">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700">Mot de passe</label>
            <input type="password" name="password"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                placeholder="••••••••">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                placeholder="••••••••">
        </div>

        <div>
            <label class="block mb-1 text-sm font-semibold text-gray-700">Rôle</label>
            <select name="categorie" id="roleSelect"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                required onchange="toggleFields()">
                <option value="" disabled {{ old('categorie') ? '' : 'selected' }}>Sélectionner un rôle</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('categorie', $user->categorie) == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="classeFieldEtudiant" style="display: none;">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Classe</label>
            <select name="classe_id"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition">
                <option value="">Sélectionner une classe</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}" {{ old('classe_id', $user->classe_id) == $classe->id ? 'selected' : '' }}>
                        {{ $classe->annee }} - {{ $classe->filiere->nom }} ({{ $classe->nom }})
                    </option>
                @endforeach
            </select>
        </div>

        <div id="classeFieldCoordinateur" style="display: none;">
            <label class="block mb-1 text-sm font-semibold text-gray-700">Classes (pour le coordinateur)</label>
            <select name="classe_id_coordinateur[]"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-violet-400 transition"
                multiple>
                <option value="" disabled {{ old('classe_id_coordinateur') ? '' : 'selected' }}>Sélectionner des classes</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}"
                        {{ (is_array(old('classe_id_coordinateur')) && in_array($classe->id, old('classe_id_coordinateur'))) ? 'selected' : ''}}>
                        {{ $classe->annee }} - {{ $classe->filiere->nom }} ({{ $classe->nom }})
                    </option>
                @endforeach
            </select>
            <small class="text-gray-500">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs classes.</small>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-tr from-violet-600 to-purple-500 text-white px-6 py-2.5 rounded-lg shadow hover:from-violet-700 hover:to-purple-600 transition-all font-semibold text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Mettre à jour
            </button>
            <a href="{{ route('admin.users') }}"
                class="text-gray-500 hover:text-violet-700 font-medium transition underline underline-offset-2">Annuler</a>
        </div>
    </form>
</div>

<script>
    function toggleFields() {
        const roleSelect = document.getElementById('roleSelect');
        const classeFieldEtudiant = document.getElementById('classeFieldEtudiant');
        const classeFieldCoordinateur = document.getElementById('classeFieldCoordinateur');

        // Masquer tous les champs de classe
        classeFieldEtudiant.style.display = 'none';
        classeFieldCoordinateur.style.display = 'none';

        if (roleSelect.value === 'etudiant') {
            classeFieldEtudiant.style.display = 'block';
        } else if (roleSelect.value === 'coordinateur') {
            classeFieldCoordinateur.style.display = 'block';
        }
    }

    // Initialiser l'affichage en fonction de la valeur actuelle
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
    });
</script>
@endsection
