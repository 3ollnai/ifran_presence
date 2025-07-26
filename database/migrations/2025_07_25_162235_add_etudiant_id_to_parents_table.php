<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEtudiantIdToParentsTable extends Migration
{
    public function up()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->unsignedBigInteger('etudiant_id')->after('user_id'); // Ajout de la colonne etudiant_id

            // Ajout de la contrainte de clé étrangère si nécessaire
            $table->foreign('etudiant_id')->references('id')->on('etudiants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropForeign(['etudiant_id']);
            $table->dropColumn('etudiant_id');
        });
    }
}
