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
        $etudiants_count = Etudiant::count();
        $seances_count = Seance::where('professeur_id', Auth::id())->count();

        $professeur = Professeur::where('user_id', Auth::id())->first();
        $seances = Seance::with(['classe', 'module'])
            ->where('professeur_id', $professeur->id)
            ->get();

        $seancesWithStudentCount = $seances->map(function ($seance) {
            $studentCount = Etudiant::where('classe_id', $seance->classe->id)->count();
            $date = \Carbon\Carbon::parse($seance->date);

            return [
                'id' => $seance->id,
                'date' => $date->format('Y-m-d'),
                'module' => $seance->module->nom,
                'classe' => $seance->classe->nom,
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
            // Récupérer la présence existante pour l'étudiant
            $presence = Presence::where('seance_id', $seance->id)
                ->where('eleve_id', $etudiant->id)
                ->first();

            if ($presence) {
                // Récupérer le dernier statut de présence
                $statut = StatutPresence::where('presence_id', $presence->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Vérifier si le statut existe avant de le mettre à jour
                if ($statut) {
                    if (array_key_exists($etudiant->id, $etudiants)) {
                        if ($etudiants[$etudiant->id] == 'Retard') {
                            $statut->update(['statut' => 'Retard']);
                        } else {
                            $statut->update(['statut' => 'Présent']);
                        }
                    } else {
                        $statut->update(['statut' => 'Absent']);
                    }
                }
            } else {
                // Crée une nouvelle présence
                $presence = Presence::create([
                    'seance_id' => $seance->id,
                    'eleve_id' => $etudiant->id,
                ]);

                // Crée un nouveau statut de présence
                StatutPresence::create([
                    'presence_id' => $presence->id,
                    'statut' => array_key_exists($etudiant->id, $etudiants) ? ($etudiants[$etudiant->id] == 'Retard' ? 'Retard' : 'Présent') : 'Absent',
                ]);
            }
        }

        return redirect()->route('professeur.index')->with('success', 'Présences enregistrées avec succès.');
    }

    public function showSeance($id)
    {
        // Récupérer la séance par son ID avec les relations
        $seance = Seance::with(['classe.etudiants.user', 'module', 'presences.statut'])->findOrFail($id);

        // Retourner la vue avec les détails de la séance
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
            $seanceEtudiantCount[$seance->id] = $seance->classe->etudiants->count();
        }

        $absencesNonJustifiees = Presence::whereHas('statut', function ($query) {
            $query->where('statut', 'Absent');
        })
            ->whereHas('seance', function ($query) use ($professeur) {
                $query->where('professeur_id', $professeur->id);
            })
            ->with(['seance', 'etudiant.user'])
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();

        $totalClassesEnseignees = $seances->count();
        $classesCurrentMois = Seance::where('professeur_id', $professeur->id)
            ->whereMonth('date', now()->month)
            ->count();

        $presenceChartLabels = [];
        $presenceChartData = [];

        foreach ($seances as $seance) {
            $presenceChartLabels[] = Carbon::parse($seance->date)->format('d/m');
            $presenceChartData[] = $seance->presences->where('statut.statut', 'Présent')->count() / $seance->classe->etudiants->count() * 100;
        }

        $etudiantsTotal = Etudiant::count();
        $tauxPresenceMoyen = round(array_sum($presenceChartData) / count($presenceChartData), 2);

        $absencesRecentes = Presence::whereHas('seance', function ($query) use ($professeur) {
            $query->where('professeur_id', $professeur->id);
        })
            ->with(['seance', 'seance.module', 'etudiant.user', 'statut'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('professeur.historique-presence', [
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'totalClassesEnseignees' => $totalClassesEnseignees,
            'classesCurrentMois' => $classesCurrentMois,
            'presenceChartLabels' => $presenceChartLabels,
            'presenceChartData' => $presenceChartData,
            'etudiantsTotal' => $etudiantsTotal,
            'tauxPresenceMoyen' => $tauxPresenceMoyen,
            'absencesRecentes' => $absencesRecentes,
            'seances' => $seances,
            'seanceEtudiantCount' => $seanceEtudiantCount,
        ]);
    }
}
