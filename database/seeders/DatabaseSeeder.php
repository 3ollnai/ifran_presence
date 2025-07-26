<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ModulesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run()
{
    $this->call([
        RoleSeeder::class,
        FiliereClasseSeeder::class,
        EtudiantSeeder::class,
        ProfesseurSeeder::class,
        ModuleSeeder::class,
        SeanceSeeder::class,
        TypeCoursSeeder::class,

    ]);
}
}
