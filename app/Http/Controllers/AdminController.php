<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Etudiant;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Page d'accueil de l'admin
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.index', compact('users'));
    }

    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    // Affichage du formulaire d'ajout d'utilisateur
    public function createUser()
    {
        // Liste des rôles possibles (cohérence avec Spatie)
        $roles = ['administrateur', 'professeur', 'coordinateur', 'etudiant', 'parent'];
        $classes = Classe::all();
        $users = User::paginate(10);

        return view('admin.create-user', compact('roles', 'classes', 'users'));
    }

    // Traitement de l'ajout d'utilisateur
    public function storeUser(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'categorie' => 'required|in:administrateur,professeur,coordinateur,etudiant,parent',
            'classe_id' => 'nullable|exists:classes,id',
            'etudiant_id' => 'nullable|exists:users,id',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'categorie' => $request->categorie,
            'classe_id' => $request->classe_id,
        ]);

        // Attribution du rôle Spatie
        $user->assignRole($request->categorie);

        // Gestion des rôles
        if ($request->categorie === 'etudiant') {
            \App\Models\Etudiant::create([
                'user_id' => $user->id,
                'classe_id' => $request->classe_id,
                'matricule' => 'MAT-' . strtoupper(uniqid()),
            ]);
        } elseif ($request->categorie === 'parent') {
            if ($request->etudiant_id) {
                \App\Models\Parents::create([
                    'user_id' => $user->id,
                    'etudiant_id' => $request->etudiant_id,
                ]);
            }
        } elseif ($request->categorie === 'coordinateur') {
            \App\Models\Coordinateur::create([
                'user_id' => $user->id,
                'classe_id' => $request->classe_id,
            ]);
        } elseif ($request->categorie === 'professeur') {
            \App\Models\Professeur::create([
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'Utilisateur ajouté avec succès !');
    }

    // Affichage du formulaire de modification d'utilisateur
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = ['administrateur', 'professeur', 'coordinateur', 'etudiant', 'parent'];
        $classes = Classe::all();
        return view('admin.edit-user', compact('user', 'roles', 'classes'));
    }

    public function updateUser(Request $request, $id)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'password' => 'nullable|string|min:6|confirmed',
        'categorie' => 'required|in:administrateur,professeur,coordinateur,etudiant,parent',
        'classe_id' => 'nullable|exists:classes,id',
    ]);

    $user = User::findOrFail($id);
    $user->nom = $request->nom;
    $user->prenom = $request->prenom;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->categorie = $request->categorie;
    $user->classe_id = $request->classe_id;
    $user->save();

    return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès !');
}


    // Affichage de la liste des classes et des filières
public function showClassesAndFilieres()
{
    $classes = Classe::with('filiere')->get();
    $filieres = Filiere::all();

    foreach ($classes as $classe) {
        $classe->load('etudiants.user');
    }

    return view('admin.classes-filieres', compact('classes', 'filieres'));
}


    // Création d'une nouvelle classe
    public function createClasse(Request $request)
    {
        $request->validate([
            'annee' => 'required|string',
            'filiere_id' => 'required|exists:filieres,id',
            'nom' => 'nullable|string',
        ]);

        $classe = Classe::create([
            'annee' => $request->annee,
            'filiere_id' => $request->filiere_id,
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.classes-filieres')->with('success', 'Classe créée avec succès !');
    }

    // Association d'un étudiant à une classe
    public function assignEtudiantToClasse(Request $request, $etudiantId)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
        ]);

        $etudiant = User::findOrFail($etudiantId);
        $etudiant->classe_id = $request->classe_id;
        $etudiant->save();

        return redirect()->route('admin.users')->with('success', 'Étudiant assigné à la classe avec succès !');
    }

    // Suppression d'un utilisateur
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès !');
    }
}
