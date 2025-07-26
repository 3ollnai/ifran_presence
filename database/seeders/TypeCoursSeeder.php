<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeCours;

class TypeCoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typesCours = ['presentiel', 'e-learning', 'workshop'];

        foreach ($typesCours as $type) {
            TypeCours::create([
                'nom' => $type
            ]);
        }
    }
}
