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

    // Récupérer les absences justifiées
    $absencesjustifiees = Presence::whereHas('statut', function ($query) {
        $query->where('statut', 'Justifié');
    })
    ->whereHas('seance', function ($query) use ($professeur) {
        $query->where('professeur_id', $professeur->id);
    })
    ->get();

    // Récupérer les absences récentes
    $absencesRecentes = Presence::whereHas('statut', function ($query) {
        $query->where('statut', 'Absent');
    })
    ->whereHas('seance', function ($query) use ($professeur) {
        $query->where('professeur_id', $professeur->id);
    })
    ->with(['seance', 'etudiant.user'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

    // Calculer le taux de présence moyen
    $etudiantsTotal = Etudiant::count();
    $totalPresences = Presence::whereHas('seance', function ($query) use ($professeur) {
        $query->where('professeur_id', $professeur->id);
    })->count();
    $totalClassesEnseignees = $seances->count();
    $tauxPresenceMoyen = $etudiantsTotal > 0 ? ($totalPresences / ($etudiantsTotal * $totalClassesEnseignees)) * 100 : 0;

    return view('professeur.index', [
        'etudiants_count' => $etudiants_count,
        'seances_count' => $seances_count,
        'seancesWithStudentCount' => $seancesWithStudentCount,
        'absencesjustifiees' => $absencesjustifiees,
        'absencesRecentes' => $absencesRecentes,
        'tauxPresenceMoyen' => $tauxPresenceMoyen,
    ]);
}

public function showSeance($id)
{
    $seance = Seance::findOrFail($id);
    $etudiants = $seance->classe->etudiants;

    return view('professeur.showSeance', [
        'seance' => $seance,
        'etudiants' => $etudiants,
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

public function historique()
{
    $professeur = Professeur::where('user_id', Auth::id())->first();
    $seances = Seance::where('professeur_id', $professeur->id)
        ->with(['classe', 'module', 'presences', 'presences.etudiant.user', 'presences.justificationAbsence'])
        ->orderBy('date', 'desc')
        ->get();

    $seanceEtudiantCount = [];
    foreach ($seances as $seance) {
        $seanceEtudiantCount[$seance->id] = $seance->classe ? $seance->classe->etudiants()->count() : 0;
    }

    // Récupérer les absences (justifiées et non justifiées)
    $absences = Presence::whereHas('seance', function ($query) use ($professeur) {
        $query->where('professeur_id', $professeur->id);
    })
    ->with(['seance', 'etudiant.user', 'justificationAbsence'])
    ->orderBy('created_at', 'desc')
    ->get();

    // Séparer les absences justifiées et non justifiées
    $absencesJustifiees = $absences->filter(function ($presence) {
        return $presence->justifie; // Assurez-vous que le champ 'justifie' est bien utilisé
    });

    $absencesNonJustifiees = $absences->filter(function ($presence) {
        return !$presence->justifie;
    });

    // Récupérer les absences récentes pour le mois actuel
    $absencesRecentes = Presence::whereHas('statut', function ($query) {
        $query->where('statut', 'Absent');
    })
    ->whereHas('seance', function ($query) use ($professeur) {
        $query->where('professeur_id', $professeur->id);
    })
    ->with(['seance', 'etudiant.user'])
    ->whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

    $totalClassesEnseignees = $seances->count();
    $classesCurrentMois = Seance::where('professeur_id', $professeur->id)
        ->whereMonth('date', Carbon::now()->month)
        ->count();

    // Calculer le taux de présence pour le mois actuel
    $totalPresencesMois = Presence::whereHas('seance', function ($query) use ($professeur) {
        $query->where('professeur_id', $professeur->id);
    })
    ->whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();

    $etudiantsTotal = Etudiant::count();
    $tauxPresenceMoyen = $etudiantsTotal > 0 ? ($totalPresencesMois / ($etudiantsTotal * $classesCurrentMois)) * 100 : 0;

    // Récupérer les données pour le graphique
    $presenceStats = [];
    foreach ($seances as $seance) {
        // Convertir la date en objet Carbon
        $seanceDate = Carbon::parse($seance->date);

        if ($seanceDate->month == Carbon::now()->month && $seanceDate->year == Carbon::now()->year) {
            $totalEtudiants = $seance->classe ? $seance->classe->etudiants()->count() : 0;
            $totalPresences = $seance->presences()->count();
            $tauxPresence = $totalEtudiants > 0 ? ($totalPresences / $totalEtudiants) * 100 : 0;

            $presenceStats[$seanceDate->format('d/m/Y')] = $tauxPresence;
        }
    }

    // Convertir les données en format pour le graphique
    $labels = array_keys($presenceStats);
    $data = array_values($presenceStats);

    return view('professeur.historique-presence', [
        'absencesNonJustifiees' => $absencesNonJustifiees,
        'absencesJustifiees' => $absencesJustifiees, // Ajout des absences justifiées
        'totalClassesEnseignees' => $totalClassesEnseignees,
        'seanceEtudiantCount' => $seanceEtudiantCount,
        'seances' => $seances,
        'classesCurrentMois' => $classesCurrentMois,
        'tauxPresenceMoyen' => $tauxPresenceMoyen,
        'etudiantsTotal' => $etudiantsTotal,
        'absencesRecentes' => $absencesRecentes, // Assurez-vous que cette variable est définie
        'presenceLabels' => json_encode($labels),
        'presenceData' => json_encode($data),
    ]);
}

}
