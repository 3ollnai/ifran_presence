<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Presence;
use App\Models\StatutPresence;
use App\Models\Module;
use App\Models\StatutJustification;
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

        // Calcul des données pour le graphique des présences par classe
        $classe_labels = [];
        $classe_presences = [];
        foreach ($classes as $classe) {
            $classe_labels[] = $classe->nom;

            $totalSeances = Seance::where('classe_id', $classe->id)->count();
            $totalPresences = StatutPresence::whereHas('presence', function ($query) use ($classe) {
                $query->whereHas('seance', function ($q) use ($classe) {
                    $q->where('classe_id', $classe->id);
                });
            })->where('statut', 'Présent')->count();
            $totalAbsences = StatutPresence::whereHas('presence', function ($query) use ($classe) {
                $query->whereHas('seance', function ($q) use ($classe) {
                    $q->where('classe_id', $classe->id);
                });
            })->where('statut', 'Absent')->count();

            $tauxPresenceMoyen = ($totalSeances > 0) ? round(($totalPresences / ($totalPresences + $totalAbsences)) * 100, 2) : 0;
            $classe_presences[] = $tauxPresenceMoyen;
        }

        // Calcul des données pour le graphique des présences par séance
        $seance_labels = Seance::orderBy('date', 'desc')->take(10)->pluck('date')->toArray();
        $seance_presences = [];
        foreach ($seance_labels as $seanceDate) {
            $totalPresences = StatutPresence::whereHas('presence', function ($query) use ($seanceDate) {
                $query->whereHas('seance', function ($q) use ($seanceDate) {
                    $q->where('date', $seanceDate);
                });
            })->where('statut', 'Présent')->count();
            $totalAbsences = StatutPresence::whereHas('presence', function ($query) use ($seanceDate) {
                $query->whereHas('seance', function ($q) use ($seanceDate) {
                    $q->where('date', $seanceDate);
                });
            })->where('statut', 'Absent')->count();
            $totalEtudiants = Etudiant::count();
            $seance_presences[] = ($totalEtudiants > 0) ? min(100, round(($totalPresences / ($totalPresences + $totalAbsences)) * 100, 2)) : 0;
        }

        return view('coordinateur.index', compact('classes', 'etudiants_count', 'professeur_count', 'seances_count', 'classe_count', 'classe_labels', 'classe_presences', 'seance_labels', 'seance_presences'));
    }

    /**
     * Liste des séances
     */
    public function seances(Request $request)
    {
        $module_id = $request->input('module_id');
        $query = Seance::with(['module', 'typeCours', 'professeur.user', 'classe']);

        if ($module_id) {
            $query->where('module_id', $module_id);
        }

        $seances = $query->orderBy('date')->paginate(10);
        return view('coordinateur.seances', compact('module_id', 'seances'));
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

    /**
     * Mise à jour d'une séance
     */
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

    /**
     * Suppression d'une séance
     */
    public function destroySeance($id)
    {
        $seance = Seance::findOrFail($id);
        $seance->delete();
        return redirect()->route('coordinateur.seances')->with('success', 'Séance supprimée avec succès.');
    }

    /**
     * Affichage de l'emploi du temps
     */
    public function emploi(Request $request)
    {
        $query = Seance::with(['classe', 'typeCours', 'professeur.user', 'module']);

        // Filtrer par module
        if ($selectedModule = $request->input('module')) {
            $query->whereHas('module', function ($q) use ($selectedModule) {
                $q->where('nom', $selectedModule);
            });
        }

        // Filtrer par classe
        if ($selectedClasse = $request->input('classe')) {
            $query->whereHas('classe', function ($q) use ($selectedClasse) {
                $q->where('nom', $selectedClasse);
            });
        }

        // Filtrer par date
        if ($jourDebut = $request->input('jour_debut') && $jourFin = $request->input('jour_fin')) {
            $query->whereBetween('date', [date('Y-m-d', strtotime($jourDebut)), date('Y-m-d', strtotime($jourFin))]);
        }

        $seances = $query->orderBy('date', 'desc')->paginate(12);
        $groupedSeances = $seances->groupBy(function ($seance) {
            return $seance->classe ? $seance->classe->nom : 'Non assigné';
        });

        return view('coordinateur.emploi', compact('groupedSeances', 'selectedClasse', 'jourDebut', 'jourFin', 'seances'));
    }

    /**
     * Saisie des présences pour une séance (par le coordinateur)
     */
    public function presence($seance_id)
    {
        $seance = Seance::with(['classe', 'professeur.user'])->findOrFail($seance_id);
        $etudiants = Etudiant::where('classe_id', $seance->classe_id)->get();
        $presences = Presence::where('seance_id', $seance_id)->get()->keyBy('eleve_id');

        return view('coordinateur.presence', compact('seance', 'etudiants', 'presences'));
    }

    /**
     * Enregistrement des présences pour une séance
     */
    public function storePresence(Request $request, $seance_id)
    {
        $seance = Seance::findOrFail($seance_id);
        $etudiants = Etudiant::where('classe_id', $seance->classe_id)->get();

        foreach ($etudiants as $etudiant) {
            $statut = $request->input('presence.' . $etudiant->id);
            Presence::updateOrCreate(
                ['seance_id' => $seance_id, 'eleve_id' => $etudiant->id],
                ['statut' => $statut, 'justifie' => false]
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

        $totalSeances = Seance::count();
        $totalPresences = StatutPresence::where('statut', 'Présent')->count();
        $taux_presence_moyen = ($totalSeances > 0) ? round(($totalPresences / $totalSeances) * 100, 2) : 0;

        $demandes = Presence::with(['etudiant', 'etudiant.classe', 'etudiant.user'])
            ->where('justifie', false)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('coordinateur.absences', compact('demandes_en_attente', 'demandes_approuvees', 'total_demandes', 'demandes', 'taux_presence_moyen'));
    }

    /**
     * Justification d'une absence
     */
    public function justifierAbsence(Request $request, $presence_id)
    {
        $presence = Presence::findOrFail($presence_id); // Récupérer la présence par ID

        // Créer un nouveau statut de justification
        $statutJustification = new StatutJustification();
        $statutJustification->presence_id = $presence->id;

        $message = '';
        if ($request->has('accepter')) {
            $statutJustification->statut = 'Acceptée';
            $presence->justifie = true;
            $message = 'Absence justifiée.';
        } elseif ($request->has('rejeter')) {
            $statutJustification->statut = 'Rejetée';
            $presence->justifie = false;
            $message = 'Absence rejetée.';
        } else {
            return back()->withErrors('Vous devez sélectionner une action (Accepter ou Rejeter).');
        }

        $statutJustification->save();
        $presence->save();

        // Rediriger vers la vue des absences avec un message de succès
        return redirect()->route('absences')->with('success', $message);
    }

    /**
     * Statistiques et graphiques (taux de présence, etc.)
     */
    public function statsClasse($classe_id)
    {
        $classe = Classe::findOrFail($classe_id);

        // Calcul du taux de présence moyen pour la classe
        $totalSeances = Seance::where('classe_id', $classe_id)->count();
        $totalPresences = Presence::whereHas('seance', function ($query) use ($classe_id) {
            $query->where('classe_id', $classe_id);
        })->where('statut', 'Présent')->count();

        $tauxPresenceMoyen = ($totalSeances > 0) ? round(($totalPresences / $totalSeances) * 100, 2) : 0;

        // Autres calculs à faire ici pour les autres stats

        return view('coordinateur.stats', compact('classe', 'tauxPresenceMoyen'));
    }
}
