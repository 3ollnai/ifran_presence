<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            // Modules pour la filière Développement Web
            [
                'nom' => 'HTML & CSS',
                'niveau' => 'Bachelor 1',
                'filiere' => 'Développement Web',
                'professeur_id' => 1,
            ],
            [
                'nom' => 'JavaScript',
                'niveau' => 'Bachelor 1',
                'filiere' => 'Développement Web',
                'professeur_id' => 2,
            ],
            [
                'nom' => 'PHP et Frameworks',
                'niveau' => 'Bachelor 2',
                'filiere' => 'Développement Web',
                'professeur_id' => 3,
            ],
            [
                'nom' => 'Bases de Données Web',
                'niveau' => 'Bachelor 2',
                'filiere' => 'Développement Web',
                'professeur_id' => 4,
            ],
            [
                'nom' => 'Développement Front-end Avancé',
                'niveau' => 'Bachelor 3',
                'filiere' => 'Développement Web',
                'professeur_id' => 5,
            ],
            [
                'nom' => 'Développement Back-end Avancé',
                'niveau' => 'Bachelor 3',
                'filiere' => 'Développement Web',
                'professeur_id' => 6,
            ],

            // Modules pour la filière Communication et Création
            [
                'nom' => 'Introduction à la Communication',
                'niveau' => 'Bachelor 1',
                'filiere' => 'Communication et Création',
                'professeur_id' => 7,
            ],
            [
                'nom' => 'Stratégies de Communication',
                'niveau' => 'Bachelor 1',
                'filiere' => 'Communication et Création',
                'professeur_id' => 1, // Réutilisation d'un professeur
            ],
            [
                'nom' => 'Création de Contenu Numérique',
                'niveau' => 'Bachelor 2',
                'filiere' => 'Communication et Création',
                'professeur_id' => 2, // Réutilisation d'un professeur
            ],
            [
                'nom' => 'Design Graphique',
                'niveau' => 'Bachelor 2',
                'filiere' => 'Communication et Création',
                'professeur_id' => 3, // Réutilisation d'un professeur
            ],
            [
                'nom' => 'Communication Visuelle',
                'niveau' => 'Bachelor 3',
                'filiere' => 'Communication et Création',
                'professeur_id' => 4, // Réutilisation d'un professeur
            ],
            [
                'nom' => 'Gestion de Projet Créatif',
                'niveau' => 'Bachelor 3',
                'filiere' => 'Communication et Création',
                'professeur_id' => 5, // Réutilisation d'un professeur
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
