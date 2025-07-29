<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      Schema::create('academic_years', function (Blueprint $table) {
          $table->id();
          $table->string('libelle');
          $table->date('date_debut');
          $table->date('date_fin');
          $table->timestamps();
      });

      // Ajout de la relation entre la classe et l'année académique
      Schema::table('classes', function (Blueprint $table) {
          $table->unsignedBigInteger('academic_year_id')->nullable();
          $table->foreign('academic_year_id')->references('id')->on('academic_years');
      });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::table('classes', function (Blueprint $table) {
          $table->dropForeign(['academic_year_id']);
          $table->dropColumn('academic_year_id');
      });

      Schema::dropIfExists('academic_years');
  }
}

