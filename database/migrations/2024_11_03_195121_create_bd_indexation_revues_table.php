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
        Schema::create('bdindexation_revue', function (Blueprint $table) {
            $table->foreignId('idBDInd')->constrained('bd_indexations', 'idBDIndex'); // Clé étrangère vers bd_indexations.idBDIndex
            $table->foreignId('idRevue')->constrained('revues', 'idRevue');            // Clé étrangère vers revues.idRevue
            $table->date('dateDebut')->nullable();                                                 // Champ requis pour la date de début
            $table->date('dateFin')->nullable();                                                   // Champ requis pour la date de fin
            $table->primary(['idBDInd', 'idRevue']);                                   // Définir la clé primaire composite
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bd_indexation_revues');
    }
};
