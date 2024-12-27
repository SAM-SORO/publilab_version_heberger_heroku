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
        Schema::create('doctorant_chercheur', function (Blueprint $table) {
            $table->foreignId('idDoc')->constrained('doctorants', 'idDoc');    // Clé étrangère vers doctorants.idDoc
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch'); // Clé étrangère vers chercheurs.idCherch
            $table->date('dateDebut');                                          // Champ requis pour la date de début
            $table->primary(['idDoc', 'idCherch']);                             // Définir la clé primaire composite
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctorant_chercheurs');
    }
};
