<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EtudiantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les filières existantes
        $filieres = Filiere::all();

        // Créer un générateur Faker avec la locale 'fr_CI'
        $faker = Faker::create('fr_CI');

        // Créer 10 étudiants par classe
        foreach ($filieres as $filiere) {
            $classes = Classe::where('filiere_id', $filiere->id)->get();
            foreach ($classes as $classe) {
                for ($i = 1; $i <= 10; $i++) {
                    $user = User::create([
                        'prenom' => $faker->firstName(),
                        'nom' => $faker->lastName(),
                        'email' => 'etudiant' . $classe->id . '-' . $i . '@ifran.ci',
                        'password' => bcrypt('password'),
                        'categorie' => 'etudiant',
                    ]);

                    Etudiant::create([
                        'user_id' => $user->id,
                        'classe_id' => $classe->id,
                        'matricule' => 'MAT-' . $classe->id . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }
    }
}
