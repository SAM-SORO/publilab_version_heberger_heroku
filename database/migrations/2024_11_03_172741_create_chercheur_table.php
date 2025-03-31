<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chercheurs', function (Blueprint $table) {
            $table->id('idCherch');
            $table->string('nomCherch', 30);
            $table->string('prenomCherch', 100)->nullable();
            $table->enum('genreCherch', ['M', 'F'])->nullable();
            $table->string('matriculeCherch', 20)->nullable();
            $table->string('password');
            $table->string('emploiCherch', 50)->nullable();
            $table->string('departementCherch', 100)->nullable();
            $table->string('fonctionAdministrativeCherch', 100)->nullable();
            $table->string('specialiteCherch', 100)->nullable();
            $table->string('emailCherch', 100)->nullable();
            $table->date('dateNaissCherch')->nullable();
            $table->date('dateArriveeCherch')->nullable();
            $table->string('telCherch', 30)->nullable();
            $table->unsignedBigInteger('idUMRI')->nullable();
            $table->unsignedBigInteger('idLabo')->nullable();
            $table->date('dateAffectationLabo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chercheurs');
    }
};
