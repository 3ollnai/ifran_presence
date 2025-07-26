<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
public function run()
{
    Role::firstOrCreate(['name' => 'administrateur']);
    Role::firstOrCreate(['name' => 'coordinateur']);
    Role::firstOrCreate(['name' => 'professeur']);
    Role::firstOrCreate(['name' => 'etudiant']);
    Role::firstOrCreate(['name' => 'parent']);
}

}
