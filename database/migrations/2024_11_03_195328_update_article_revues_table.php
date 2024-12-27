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
        Schema::create('article_revue', function (Blueprint $table) {
            $table->foreignId('idArticle')->constrained('articles', 'idArticle');  // Clé étrangère vers articles.idArticle
            $table->foreignId('idRevue')->constrained('revues', 'idRevue');        // Clé étrangère vers revues.idRevue
            $table->date('datePubArt');                                             // Champ requis pour la date de publication
            $table->integer('numero')->nullable();
            $table->integer('volume')->nullable();                                   // Champ nullable pour le volume
            $table->integer('pageDebut');                                        // Champ requis pour la page de début
            $table->integer('pageFin');                                           // Champ requis pour la page de fin
            $table->timestamps();                                                   // Champs created_at et updated_at
            $table->primary(['idArticle', 'idRevue']);                              // Définir la clé primaire composite
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_revues');
    }
};
