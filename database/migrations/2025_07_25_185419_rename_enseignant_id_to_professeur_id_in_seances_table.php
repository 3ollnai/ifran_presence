<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEnseignantIdToProfesseurIdInSeancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->renameColumn('enseignant_id', 'professeur_id');
            $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->renameColumn('professeur_id', 'enseignant_id');
            $table->foreign('enseignant_id')->references('id')->on('professeurs')->onDelete('cascade');
        });
    }
}
