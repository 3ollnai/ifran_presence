<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('niveau');
            $table->unsignedBigInteger('professeur_id')->nullable();
            $table->timestamps();

            $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
