<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Presence;
use App\Models\Module;
use Carbon\Carbon;
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
        $classes = Classe::all();

        // Comptez le nombre d'étudiants et d'enseignants
        $etudiants_count = Etudiant::count();
        $professeur_count = Professeur::count();
        $seances_count = Seance::count();
        $classe_count = Classe::count();

        return view('coordinateur.index', compact('classes', 'etudiants_count', 'professeur_count', 'seances_count', 'classe_count'));
    }

    /**
     * Liste des séances
     */
    public function seances(Request $request)
    {
        $module_id = $request->input('module_id');

        if ($module_id) {
            $module = Module::findOrFail($module_id);
            $seances = Seance::with(['module', 'typeCours', 'professeur.user', 'classe'])
                ->where('module_id', $module_id)
                ->orderBy('date')
                ->paginate(10);
        } else {
            $module = null;
            $seances = Seance::with(['module', 'typeCours', 'professeur.user', 'classe'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('coordinateur.seances', compact('module', 'seances'));
    }

    /**
     * Affichage du formulaire de création d'une séance
     */
    public function createSeance()
    {
        $professeurs = Professeur::with('user:id,nom,prenom')->get();
        $classes = Classe::all();
        $modules = Module::all();
        $typesCours = \App\Models\TypeCours::all();

        return view('coordinateur.create-seance', compact('professeurs', 'classes', 'modules', 'typesCours'));
    }

    /**
     * Enregistrement d'une nouvelle séance
     */
    public function storeSeance(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'module_id' => 'required|exists:modules,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'type_cours_id' => 'required|exists:type_cours,id',
        ]);

        Seance::create($request->all());

        return redirect()->route('coordinateur.seances')->with('success', 'Séance ajoutée avec succès.');
    }

    /**
     * Modification d'une séance (annulation, report, etc.)
     */
    public function editSeance($seance_id)
    {
        $seance = Seance::with(['classe', 'professeur.user:id,nom,prenom'])->findOrFail($seance_id);
        $professeurs = Professeur::with('user')->get();
        $classes = Classe::all();
        $modules = Module::all();
        $typesCours = \App\Models\TypeCours::all();

        return view('coordinateur.edit-seance', compact('seance', 'professeurs', 'classes', 'modules', 'typesCours'));
    }

    public function updateSeance(Request $request, $seance_id)
    {
        $seance = Seance::findOrFail($seance_id);

        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'module_id' => 'required|exists:modules,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'type_cours_id' => 'required|exists:type_cours,id',
        ]);

        $seance->update($request->all());

        return redirect()->route('coordinateur.seances')->with('success', 'Séance modifiée avec succès.');
    }

    public function destroySeance($id)
    {
        $seance = Seance::findOrFail($id);
        $seance->delete();

        return redirect()->route('coordinateur.seances')->with('success', 'Séance supprimée avec succès.');
    }

    public function emploi(Request $request)
    {
        $query = Seance::with(['classe', 'typeCours', 'professeur.user', 'module']);

        // Filtrer par module
        $selectedModule = $request->input('module', '');
        if ($selectedModule) {
            $query->whereHas('module', function ($q) use ($selectedModule) {
                $q->where('nom', $selectedModule);
            });
        }

        // Filtrer par classe
        $selectedClasse = $request->input('classe', '');
        if ($selectedClasse) {
            $query->whereHas('classe', function ($q) use ($selectedClasse) {
                $q->where('nom', $selectedClasse);
            });
        }

        // Filtrer par date
        $jourDebut = $request->input('jour_debut');
        $jourFin = $request->input('jour_fin');
        if ($jourDebut && $jourFin) {
            $query->whereBetween('date', [date('Y-m-d', strtotime($jourDebut)), date('Y-m-d', strtotime($jourFin))]);
        }

        $seances = $query->orderBy('date', 'desc')->paginate(12);
        $groupedSeances = $seances->groupBy(function ($seance) {
            return $seance->classe ? $seance->classe->nom : 'Non assigné';
        });

        $allClasses = $groupedSeances->keys()->toArray();

        return view('coordinateur.emploi', compact('groupedSeances', 'allClasses', 'selectedClasse', 'jourDebut', 'jourFin', 'seances'));
    }

    /**
     * Saisie des présences pour une séance (par le coordinateur)
     */
    public function presence($seance_id)
    {
        $seance = Seance::with(['classe', 'professeur.user'])->findOrFail($seance_id);
        $etudiants = Etudiant::where('classe_id', $seance->classe_id)->get();
        $presences = Presence::where('seance_id', $seance_id)->get()->keyBy('etudiant_id');

        return view('coordinateur.presence', compact('seance', 'etudiants', 'presences'));
    }

    public function storePresence(Request $request, $seance_id)
    {
        $seance = Seance::findOrFail($seance_id);
        $etudiants = Etudiant::where('classe_id', $seance->classe_id)->get();

        foreach ($etudiants as $etudiant) {
            $statut = $request->input('presence.' . $etudiant->id);
            Presence::updateOrCreate(
                ['seance_id' => $seance_id, 'etudiant_id' => $etudiant->id],
                ['statut' => $statut]
            );
        }

        return back()->with('success', 'Présences enregistrées.');
    }

    /**
     * Affichage des absences à justifier
     */
    public function absences()
    {
        $demandes_en_attente = Presence::where('justifie', false)->count();
        $demandes_approuvees = Presence::where('justifie', true)->count();
        $total_demandes = Presence::count();

        $demandes = Presence::with(['etudiant', 'etudiant.classe'])
            ->where('justifie', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('coordinateur.absences', compact('demandes_en_attente', 'demandes_approuvees', 'total_demandes', 'demandes'));
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
