<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run()
{
    // Créer les filières
    DB::table('filieres')->insert([
        ['nom' => 'Développement'],
        ['nom' => 'Communication'],
        ['nom' => 'Création'],
    ]);

    // Créer les classes
    $devId = DB::table('filieres')->where('nom', 'Développement')->value('id');
    $comId = DB::table('filieres')->where('nom', 'Communication')->value('id');
    $creaId = DB::table('filieres')->where('nom', 'Création')->value('id');

    DB::table('classes')->insert([
        // Filière Développement
        ['annee' => 'B1', 'filiere_id' => $devId, 'nom' => 'B1 Développement'],
        ['annee' => 'B2', 'filiere_id' => $devId, 'nom' => 'B2 Développement'],
        ['annee' => 'B3', 'filiere_id' => $devId, 'nom' => 'B3 Développement'],

        // Filière Communication
        ['annee' => 'B1', 'filiere_id' => $comId, 'nom' => 'B1 Communication'],
        ['annee' => 'B2', 'filiere_id' => $comId, 'nom' => 'B2 Communication'],
        ['annee' => 'B3', 'filiere_id' => $comId, 'nom' => 'B3 Communication'],

        // Filière Création
        ['annee' => 'B1', 'filiere_id' => $creaId, 'nom' => 'B1 Création'],
        ['annee' => 'B2', 'filiere_id' => $creaId, 'nom' => 'B2 Création'],
        ['annee' => 'B3', 'filiere_id' => $creaId, 'nom' => 'B3 Création'],
    ]);
}

}
