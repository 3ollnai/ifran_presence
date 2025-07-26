<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssiduitesTable extends Migration
{
    public function up()
    {
        Schema::create('assiduite', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('module_id');
            $table->float('note')->default(0);
            $table->timestamps();

            $table->foreign('eleve_id')->references('id')->on('etudiants')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assiduite');
    }
}
