<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesseursTable extends Migration
{
    public function up()
    {
        Schema::create('professeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Ajoutez les timestamps si nécessaire
        });
    }

    public function down()
    {
        Schema::dropIfExists('professeurs');
        Schema::dropIfExists('user_role'); // Supprimez le schéma de la table users si nécessaire

    }
}
