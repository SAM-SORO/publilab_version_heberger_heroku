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
        Schema::create('chercheurs', function (Blueprint $table) {
            $table->id('idCherch');
            $table->string('nomCherch');                   // Champ requis
            $table->string('prenomCherch')->nullable();                // Champ requis
            $table->string('adresse')->nullable();         // Champ requis
            $table->string('telCherch')->nullable();                   // Champ nullable
            $table->string('emailCherch')->unique();       // Champ requis et unique
            $table->string('password');                    // Champ requis
            $table->string('specialite')->nullable();      // Champ nullable
            $table->foreignId('idLabo')->constrained('laboratoires', 'idLabo'); // Clé étrangère non nullable
            $table->string('dateArrivee')->nullable();     // Champ nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chercheurs');
    }
};


//

// $table->foreign('id_labo')->references('id')->on('laboratoires')->onDelete('cascade');
