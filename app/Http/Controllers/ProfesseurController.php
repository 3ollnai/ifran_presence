<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Presence;
use App\Models\StatutPresence;
use App\Models\Module;
use Carbon\Carbon;

class ProfesseurController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:professeur']);
    }

    public function index()
    {
        $professeur = Professeur::where('user_id', Auth::id())->first();

        // Compter les étudiants et les séances
        $etudiants_count = Etudiant::count();
        $seances_count = Seance::where('professeur_id', $professeur->id)->count();

        // Récupérer les séances avec les relations
        $seances = Seance::with(['classe', 'module'])
            ->where('professeur_id', $professeur->id)
            ->get();

        // Compter le nombre d'étudiants par séance
        $seancesWithStudentCount = $seances->map(function ($seance) {
            $studentCount = $seance->classe ? $seance->classe->etudiants()->count() : 0;
            return [
                'id' => $seance->id,
                'date' => Carbon::parse($seance->date)->format('Y-m-d'),
                'module' => $seance->module->nom,
                'classe' => $seance->classe ? $seance->classe->nom : 'Non assignée',
                'student_count' => $studentCount,
            ];
        });

        return view('professeur.index', [
            'etudiants_count' => $etudiants_count,
            'seances_count' => $seances_count,
            'seancesWithStudentCount' => $seancesWithStudentCount,
        ]);
    }

    public function markPresence(Request $request, $id)
    {
        $seance = Seance::findOrFail($id);
        $etudiants = $request->input('etudiants', []);

        foreach ($seance->classe->etudiants as $etudiant) {
            // Récupérer ou créer la présence de l'étudiant
            $presence = Presence::firstOrCreate([
                'seance_id' => $seance->id,
                'eleve_id' => $etudiant->id,
            ]);

            // Vérifier si l'étudiant a un statut dans la requête
            if (array_key_exists($etudiant->id, $etudiants)) {
                $statutPresence = $etudiants[$etudiant->id];

                // Mettre à jour ou créer le statut de présence
                StatutPresence::updateOrCreate(
                    ['presence_id' => $presence->id],
                    ['statut' => $statutPresence]
                );
            }
        }

        return redirect()->route('professeur.index')->with('success', 'Présences enregistrées avec succès.');
    }

    public function showSeance($id)
    {
        $seance = Seance::with(['classe.etudiants.user', 'module', 'presences.statuts'])
            ->findOrFail($id);

        // Vérifier si la séance est associée à un professeur
        $professeur = Professeur::where('user_id', Auth::id())->first();
        if ($seance->professeur_id !== $professeur->id) {
            return redirect()->route('professeur.index')->with('error', 'Accès non autorisé à cette séance.');
        }

        return view('professeur.showSeance', compact('seance'));
    }

    public function historique()
    {
        $professeur = Professeur::where('user_id', Auth::id())->first();
        $seances = Seance::where('professeur_id', $professeur->id)
            ->with(['classe', 'module', 'presences', 'presences.etudiant.user'])
            ->orderBy('date', 'desc')
            ->get();

        $seanceEtudiantCount = [];
        foreach ($seances as $seance) {
            $seanceEtudiantCount[$seance->id] = $seance->classe ? $seance->classe->etudiants()->count() : 0;
        }

        $absencesNonJustifiees = Presence::whereHas('statut', function ($query) {
            $query->where('statut', 'Absent');
        })
        ->whereHas('seance', function ($query) use ($professeur) {
            $query->where('professeur_id', $professeur->id);
        })
        ->with(['seance', 'etudiant.user'])
        ->orderBy('created_at', 'desc')
        ->get(); // Supprimer limit(1) pour obtenir toutes les absences non justifiées

        $totalClassesEnseignees = $seances->count();
        $classesCurrentMois = Seance::where('professeur_id', $professeur->id)
            ->whereMonth('date', Carbon::now()->month)
            ->count();

        $totalPresences = Presence::whereHas('seance', function ($query) use ($professeur) {
            $query->where('professeur_id', $professeur->id);
        })->count();

        $etudiantsTotal = Etudiant::count();
        $tauxPresenceMoyen = $etudiantsTotal > 0 ? ($totalPresences / ($etudiantsTotal * $totalClassesEnseignees)) * 100 : 0;

        // Récupérer les absences récentes
        $absencesRecentes = Presence::whereHas('statut', function ($query) {
            $query->where('statut', 'Absent');
        })
        ->whereHas('seance', function ($query) use ($professeur) {
            $query->where('professeur_id', $professeur->id);
        })
        ->with(['seance', 'etudiant.user'])
        ->orderBy('created_at', 'desc')
        ->take(5) // Les 5 dernières absences
        ->get();

        return view('professeur.historique-presence', [
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'totalClassesEnseignees' => $totalClassesEnseignees,
            'seanceEtudiantCount' => $seanceEtudiantCount,
            'seances' => $seances,
            'classesCurrentMois' => $classesCurrentMois,
            'tauxPresenceMoyen' => $tauxPresenceMoyen,
            'etudiantsTotal' => $etudiantsTotal,
            'absencesRecentes' => $absencesRecentes,
        ]);
    }
}
