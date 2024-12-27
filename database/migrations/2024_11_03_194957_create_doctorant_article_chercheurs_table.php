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
        Schema::create('doctorant_article_chercheur', function (Blueprint $table) {
            $table->foreignId('idDoc')->constrained('doctorants', 'idDoc');    // Clé étrangère vers doctorants.idDoc
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch'); // Clé étrangère vers chercheurs.idCherch
            $table->foreignId('idArticle')->constrained('articles', 'idArticle'); // Clé étrangère vers articles.idArticle
            $table->primary(['idDoc', 'idCherch', 'idArticle']);               // Définir la clé primaire composite
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctorant_article_chercheurs');
    }
};
