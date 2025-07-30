<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seance;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\StatutPresence;

class PresenceSeeder extends Seeder
{
    public function run()
    {
        // Récupération de toutes les séances et étudiants
        $seances = Seance::all();
        $etudiants = Etudiant::all();

        foreach ($seances as $seance) {
            foreach ($etudiants as $etudiant) {
                // Vérifier si la présence existe déjà pour cet étudiant et cette séance
                $existingPresence = Presence::where('seance_id', $seance->id)
                    ->where('eleve_id', $etudiant->id)
                    ->first();

                if (!$existingPresence) {
                    // Créer une présence pour chaque étudiant dans chaque séance si elle n'existe pas
                    $presence = Presence::create([
                        'seance_id' => $seance->id,
                        'eleve_id' => $etudiant->id,
                    ]);

                    // Déterminer aléatoirement le statut de présence
                    $statut = $this->randomStatut();

                    // Créer un statut de présence
                    StatutPresence::create([
                        'presence_id' => $presence->id,
                        'statut' => $statut,
                    ]);
                }
            }
        }
    }

    private function randomStatut()
    {
        $statuts = ['Présent', 'Absent', 'Retard'];
        return $statuts[array_rand($statuts)];
    }
}
