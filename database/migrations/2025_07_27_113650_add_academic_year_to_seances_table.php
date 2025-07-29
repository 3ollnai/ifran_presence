<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicYearToSeancesTable extends Migration
{
    public function up()
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->unsignedBigInteger('annee_academique_id')->after('salle')->nullable();

            // Définir la clé étrangère
            $table->foreign('annee_academique_id')->references('id')->on('academic_years')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('seances', function (Blueprint $table) {
            // Supprimer la clé étrangère
            $table->dropForeign(['annee_academique_id']);
            // Supprimer la colonne
            $table->dropColumn('annee_academique_id');
        });
    }
}
