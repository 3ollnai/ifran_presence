@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
    <div class="max-w-6xl mx-auto mt-12">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Gestion des utilisateurs</h1>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-tr from-violet-600 to-purple-500 text-white px-5 py-2.5 rounded-lg shadow hover:from-violet-700 hover:to-purple-600 transition-all font-semibold text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un utilisateur
            </a>
        </div>
        @if (session('success'))
            <div
                class="mb-6 flex items-center gap-3 text-green-700 bg-green-100 border border-green-200 p-4 rounded-lg shadow">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        <div class="overflow-x-auto bg-white shadow-xl rounded-xl border border-gray-100">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-violet-50 to-purple-50 text-gray-700">
                        <th class="py-3 px-6 text-left font-semibold">Nom & Prénom</th>
                        <th class="py-3 px-6 text-left font-semibold">Email</th>
                        <th class="py-3 px-6 text-left font-semibold">Catégorie</th>
                        <th class="py-3 px-6 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-violet-50 transition">
                            <td class="py-3 px-6 font-medium text-gray-900 flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-violet-200 to-purple-200 flex items-center justify-center text-violet-700 font-bold">
                                    {{ strtoupper(substr($user->nom, 0, 1)) }}{{ strtoupper(substr($user->prenom, 0, 1)) }}
                                </div>
                                {{ $user->nom }} {{ $user->prenom }}
                            </td>
                            <td class="py-3 px-6 text-gray-700">{{ $user->email }}</td>
                            <td class="py-3 px-6 capitalize">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if ($user->categorie === 'administrateur') bg-violet-100 text-violet-700
                            @elseif($user->categorie === 'professeur') bg-blue-100 text-blue-700
                            @elseif($user->categorie === 'coordinateur') bg-amber-100 text-amber-700
                            @elseif($user->categorie === 'etudiant') bg-green-100 text-green-700
                            @elseif($user->categorie === 'parent') bg-pink-100 text-pink-700
                            @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($user->categorie) }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition text-xs font-medium shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13h6m2 2a2 2 0 11-4 0 2 2 0 014 0zm-2-2V7a2 2 0 10-4 0v6" />
                                        </svg>
                                        Éditer
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 transition text-xs font-medium shadow-sm"
                                            onclick="return confirm('Supprimer cet utilisateur ?')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 px-6 text-center text-gray-400 text-lg">Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
@endsection
