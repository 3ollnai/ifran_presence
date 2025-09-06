<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Presence;
use App\Models\Etudiant;
use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    public function __construct()
    {
        // Middleware pour s'assurer que l'utilisateur est authentifié et a le rôle 'etudiant'
        $this->middleware(['auth', 'role:etudiant']);
    }

    public function index()
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a un profil étudiant
        $etudiant = $user->etudiant;
        if ($etudiant) {
            // Récupérer les informations personnelles de l'étudiant
            $informationsPersonnelles = [
                'nom' => $user->nom ?? 'Nom non disponible',
                'prenom' => $user->prenom ?? 'Prénom non disponible',
                'classe' => $etudiant->classe->nom ?? 'Classe non disponible',
                'numeroEtudiant' => $etudiant->matricule ?? 'Numéro non disponible',
            ];

            // Calculer le taux de présence
            $presences = Presence::where('eleve_id', $etudiant->id)
                                ->whereHas('seance', function ($query) {
                                    $query->whereDate('date', '>=', now()->subMonths(3)); // Derniers 3 mois
                                })
                                ->get();

            $totalSeances = $presences->count();
            $totalAbsences = Seance::where('classe_id', $etudiant->classe_id)
                                    ->whereDate('date', '>=', now()->subMonths(3))
                                    ->count();

            $tauxPresence = $totalAbsences > 0 ? ($totalSeances / $totalAbsences) * 100 : 0;

            // Définir la couleur et le message en fonction du taux de présence
            if ($tauxPresence >= 90) {
                $tauxPresenceColor = 'text-green-500';
                $tauxPresenceMessage = 'Excellent travail ce mois-ci !';
            } elseif ($tauxPresence >= 80) {
                $tauxPresenceColor = 'text-orange-500';
                $tauxPresenceMessage = 'Bon travail, continuez ainsi !';
            } else {
                $tauxPresenceColor = 'text-red-500';
                $tauxPresenceMessage = 'Votre taux de présence doit être amélioré.';
            }

            // Récupérer les notifications importantes
            $notificationsImportantes = [
                'Rappel : Cours de Javascript le 10 septembre à 9h00.',
                
            ];

            return view('etudiant.index', compact('informationsPersonnelles', 'notificationsImportantes', 'tauxPresence', 'tauxPresenceColor', 'tauxPresenceMessage'));
        } else {
            // Si l'utilisateur n'a pas de profil étudiant, redirigez-le vers une page appropriée
            return redirect()->route('login')->with('error', 'Vous n\'avez pas de profil étudiant associé à votre compte.');
        }
    }

    /**
     * Afficher l'emploi du temps de l'étudiant
     */
    public function emploiDuTemps()
    {
        $etudiant = Auth::user()->etudiant; // Récupérer l'étudiant connecté
        $seances = Seance::where('classe_id', $etudiant->classe_id)
                         ->whereDate('date', '>=', now())
                         ->orderBy('date')
                         ->get();

        return view('etudiant.emploi_du_temps', compact('seances'));
    }

    /**
     * Calculer le taux de présence et la note d'assiduité
     */
    public function tauxPresence()
    {
        $etudiant = Auth::user()->etudiant;
        $presences = Presence::where('eleve_id', $etudiant->id)
                            ->whereHas('seance', function ($query) {
                                $query->whereDate('date', '>=', now()->subMonths(3));
                            })
                            ->get();

        $resultats = [];
        foreach ($presences->groupBy('module_id') as $module_id => $modulePresences) {
            $module = Module::find($module_id);
            $totalSeances = Seance::where('module_id', $module_id)
                                ->whereHas('classe', function ($query) use ($etudiant) {
                                    $query->where('id', $etudiant->classe_id);
                                })
                                ->whereDate('date', '>=', now()->subMonths(3))
                                ->count();
            $totalPresences = $modulePresences->count();

            $tauxPresence = ($totalSeances > 0) ? ($totalPresences / $totalSeances) * 100 : 0;
            $noteAssiduite = ($totalSeances > 0) ? round(($totalPresences / $totalSeances) * 20, 2) : 0;

            $resultats[] = [
                'module' => $module,
                'taux_presence' => $tauxPresence,
                'note_assiduite' => $noteAssiduite,
            ];
        }

        return view('etudiant.taux_presence', compact('resultats'));
    }
}
