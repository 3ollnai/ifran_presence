<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seance;
use App\Models\Classe;
use App\Models\User;
use App\Models\Module;
use App\Models\TypeCours;
use Faker\Factory as Faker;

class SeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $classes = Classe::all();
        $professeurs = User::role('professeur')->get();
        $modules = Module::all();
        $typesCours = TypeCours::all();

        foreach ($classes as $classe) {
            for ($i = 0; $i < 10; $i++) {
                Seance::create([
                    'classe_id' => $classe->id,
                    'module_id' => $modules->random()->id,
                    'professeur_id' => $professeurs->random()->id,
                    'salle' => $this->generateRandomSalle($faker),
                    'date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                    'heure_debut' => $faker->time('H:i'),
                    'heure_fin' => $faker->time('H:i'),
                    'type_cours_id' => $typesCours->random()->id,
                ]);
            }
        }
    }

    /**
     * Génère un ID de salle aléatoire.
     *
     * @param \Faker\Generator $faker
     * @return int
     */
    private function generateRandomSalle($faker)
    {

        return $faker->numberBetween(1, 10);
    }
}
