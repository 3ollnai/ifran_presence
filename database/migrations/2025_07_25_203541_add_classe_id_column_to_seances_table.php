<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('seances', function (Blueprint $table) {
        $table->unsignedBigInteger('classe_id')->after('id');
        $table->foreign('classe_id')->references('id')->on('classes');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            //
        });
    }
};
