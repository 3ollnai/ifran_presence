<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresencesTable extends Migration
{
    public function up()
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seance_id');
            $table->unsignedBigInteger('eleve_id');
            $table->timestamps();

            $table->foreign('seance_id')->references('id')->on('seances')->onDelete('cascade');
            $table->foreign('eleve_id')->references('id')->on('etudiants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('presences');
    }
}
