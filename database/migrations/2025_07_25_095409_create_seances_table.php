<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeancesTable extends Migration
{
    public function up()
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('type_cours_id');
            $table->unsignedBigInteger('enseignant_id');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle')->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('type_cours_id')->references('id')->on('type_cours')->onDelete('cascade');
            $table->foreign('enseignant_id')->references('id')->on('professeurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seances');
    }
}
