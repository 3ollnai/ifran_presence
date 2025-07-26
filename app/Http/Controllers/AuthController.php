<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription de l'utilisateur.
     */
    public function register(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'nom' => ['required', 'string', 'max:255'],
        'prenom' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:6'],
    ]);

    // Par défaut, le rôle est null
    $categorie = null;

    // Attribution du rôle administrateur si c'est le premier utilisateur inscrit
    if (User::count() === 0) { // Attention : c'est === 0 pour le tout premier utilisateur
        $categorie = 'administrateur';
    }

    // Création de l'utilisateur avec la catégorie
    $user = User::create([
        'nom' => $validated['nom'],
        'prenom' => $validated['prenom'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'categorie' => $categorie,
    ]);

    // Attribution du rôle avec Spatie si c'est le premier utilisateur
    if ($categorie === 'administrateur') {
        $user->assignRole('administrateur');
    }

    Auth::login($user);

    if ($user->hasRole('administrateur')) {
        return redirect('/admin');
    } else {
        return redirect()->route('login')->with('status', 'Inscription réussie, un administrateur doit vous attribuer un rôle.');
    }
}

    /**
     * Traite la connexion de l'utilisateur.
     */
   public function login(Request $request)
{
    // Validation des informations
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Tentative de connexion
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Vérifie que $user est bien une instance de App\Models\User
        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Utilisateur non valide.',
            ]);
        }

        // Redirection selon le rôle (Spatie)
        if ($user->hasRole('administrateur')) {
            return redirect('/admin');
        } elseif ($user->hasRole('coordinateur')) {
            return redirect('/coordinateur');
        } elseif ($user->hasRole('professeur')) {
            return redirect('/professeur');
        } elseif ($user->hasRole('etudiant')) {
            return redirect('/etudiant');
        } elseif ($user->hasRole('parent')) {
            return redirect('/parent');
        } else {
            // Rôle inconnu, déconnexion par sécurité
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Rôle utilisateur inconnu. Contactez un administrateur.',
            ]);
        }
    }

    // Échec de connexion
    return back()->withErrors([
        'email' => 'Les informations de connexion sont incorrectes.',
    ])->onlyInput('email');
}

    /**
     * Déconnexion de l'utilisateur.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
