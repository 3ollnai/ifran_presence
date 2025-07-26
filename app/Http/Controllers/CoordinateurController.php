<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Presence;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CoordinateurController extends Controller
{
    public function __construct()
    {
        // Seuls les coordinateurs ont accès à ce contrôleur
        $this->middleware(['auth', 'role:coordinateur']);
    }

    /**
     * Tableau de bord : liste des classes et accès rapide aux séances
     */
    public function index()
    {
        $classes = Classe::all(); // Adapter si besoin selon le coordinateur

        // Comptez le nombre d'étudiants et d'enseignants
        $etudiants_count = Etudiant::count();
        $professeur_count = Professeur::count();
        $seances_count = Seance::count();
        $classe_count = Classe::count();

        return view('coordinateur.index', compact('classes', 'etudiants_count', 'professeur_count','seances_count','classe_count'));
    }

    /**
     * Liste des séances
     */
public function seances(Request $request)
{
    $module_id = $request->input('module_id');

    if ($module_id) {
        $module = Module::findOrFail($module_id);
        $seances = Seance::with(['module', 'typeCours', 'professeur', 'classe'])
                         ->where('module_id', $module_id)
                         ->orderBy('date')
                         ->get();
    } else {
        $module = null;
        $seances = Seance::with(['module', 'typeCours', 'professeur', 'classe'])
                         ->orderBy('date')
                         ->get();
    }

    return view('coordinateur.seances', compact('module', 'seances'));
}


    /**
     * Affichage du formulaire de création d'une séance
     */
    public function createSeance()
    {
        $classes = Classe::all();
        $enseignants = User::role('professeur')->get();
        return view('coordinateur.create-seance', compact('classes', 'enseignants'));
    }

    /**
     * Enregistrement d'une nouvelle séance
     */
    public function storeSeance(Request $request)
    {
        $request->validate([
            'classe_id'     => 'required|exists:classes,id',
            'module'        => 'required|string|max:255',
            'professeur_id' => 'required|exists:users,id',
            'date'          => 'required|date',
            'heure_debut'   => 'required',
            'heure_fin'     => 'required',
            'type'          => 'required|in:presentiel,e-learning,workshop',
        ]);

        Seance::create([
            'classe_id'     => $request->classe_id,
            'module'        => $request->module,
            'professeur_id' => $request->enseignant_id,
            'date'          => $request->date,
            'heure_debut'   => $request->heure_debut,
            'heure_fin'     => $request->heure_fin,
            'type'          => $request->type,
        ]);

        return redirect()->route('coordinateur.seances')
            ->with('success', 'Séance ajoutée avec succès.');
    }

    /**
     * Modification d'une séance (annulation, report, etc.)
     */
    public function editSeance($seance_id)
    {
        $seance = Seance::with(['classe', 'professeur'])->findOrFail($seance_id);
        $enseignants = User::role('professeur')->get();
        $classes = Classe::all();
        return view('coordinateur.edit-seance', compact('seance', 'enseignants', 'classes'));
    }

    public function updateSeance(Request $request, $seance_id)
    {
        $seance = Seance::findOrFail($seance_id);

        $request->validate([
            'classe_id'     => 'required|exists:classes,id',
            'new_date' => 'required|date',
            'module'        => 'required|string|max:255',
            'professeur_id' => 'required|exists:users,id',
            'date'          => 'required|date',
            'heure_debut'   => 'required',
            'heure_fin'     => 'required',
            'type'          => 'required|in:presentiel,e-learning,workshop',
        ]);

        $seance->update($request->all());

        return back()->with('success', 'Séance modifiée avec succès.');
        return redirect()->route('coordinateur.seances')->with('success', 'Séance reportée avec succès.');
    }

    /**
     * Saisie des présences pour une séance (par le coordinateur)
     */
    public function presence($seance_id)
    {
        $seance = Seance::with(['classe', 'professeur'])->findOrFail($seance_id);
        $etudiants = Etudiant::where('classe_id', $seance->classe_id)->get();
        $presences = Presence::where('seance_id', $seance_id)->get()->keyBy('etudiant_id');

        return view('coordinateur.presence', compact('seance', 'etudiants', 'presences'));
    }

    public function storePresence(Request $request, $seance_id)
    {
        $seance = Seance::findOrFail($seance_id);
        $etudiants = Etudiant::where('classe_id', $seance->classe_id)->get();

        foreach ($etudiants as $etudiant) {
            $statut = $request->input('presence.' . $etudiant->id); // 'present', 'retard', 'absent'
            Presence::updateOrCreate(
                ['seance_id' => $seance_id, 'etudiant_id' => $etudiant->id],
                ['statut' => $statut]
            );
        }

        return back()->with('success', 'Présences enregistrées.');
    }

    /**
     * Justification d'une absence
     */
    public function justifierAbsence(Request $request, $presence_id)
    {
        $presence = Presence::findOrFail($presence_id);

        $request->validate([
            'motif' => 'required|string|max:255',
        ]);

        $presence->justifie = true;
        $presence->motif = $request->motif;
        $presence->save();

        return back()->with('success', 'Absence justifiée.');
    }

    /**
     * Statistiques et graphiques (taux de présence, etc.)
     */
    public function statsClasse($classe_id)
    {
        $classe = Classe::findOrFail($classe_id);
        // Calculs à faire ici pour le taux de présence et autres stats
        return view('coordinateur.stats', compact('classe'));
    }
}
