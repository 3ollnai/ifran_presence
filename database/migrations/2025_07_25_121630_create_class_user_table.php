<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassUserTable extends Migration
{
    public function up()
    {
        Schema::create('class_user', function (Blueprint $table) {
            $table->id(); // Optionnel, mais peut être utile pour la gestion des enregistrements
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Pour garder une trace des dates de création et de mise à jour
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_user');
    }
}
