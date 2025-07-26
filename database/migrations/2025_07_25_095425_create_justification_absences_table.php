<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustificationAbsencesTable extends Migration
{
    public function up()
    {
        Schema::create('justification_absence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presence_id');
            $table->text('motif');
            $table->string('document')->nullable();
            $table->timestamps();

            $table->foreign('presence_id')->references('id')->on('presences')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('justification_absence');
    }
}
