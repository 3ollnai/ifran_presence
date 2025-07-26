<?php

namespace Database\Seeders;

use App\Models\Professeur;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfesseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un générateur Faker avec la locale 'fr_CI'
        $faker = Faker::create('fr_CI');

        // Créer 6 professeurs
        for ($i = 1; $i <= 6; $i++) {
            $user = User::create([
                'prenom' => $faker->firstName(),
                'nom' => $faker->lastName(),
                'email' => 'professeur' . $i . '@ifran.ci',
                'password' => bcrypt('password'),
                'categorie' => 'professeur',
            ]);

            if ($user->categorie === 'professeur') {
                Professeur::create([
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
