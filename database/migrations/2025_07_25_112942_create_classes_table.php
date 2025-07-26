<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('classes', function (Blueprint $table) {
    $table->id();
    $table->string('annee'); // ex: B1, B2, B3
    $table->foreignId('filiere_id')->constrained('filieres')->onDelete('cascade');
    $table->string('nom')->nullable(); // ex: "B1 Dev"
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
