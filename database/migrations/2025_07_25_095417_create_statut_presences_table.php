<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatutPresencesTable extends Migration
{
    public function up()
    {
        Schema::create('statut_presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presence_id');
            $table->enum('statut', ['Présent', 'Absent', 'Retard']);
            $table->timestamps();

            $table->foreign('presence_id')->references('id')->on('presences')->onDelete('cascade');
        });
    }

   public function down()
{
    Schema::dropIfExists('statut_presences'); // Correction ici
}

}
