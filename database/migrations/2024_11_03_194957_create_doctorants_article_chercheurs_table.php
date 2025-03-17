<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctorant_article_chercheur', function (Blueprint $table) {
            $table->foreignId('idDoc')->constrained('doctorants', 'idDoc');
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch');
            $table->foreignId('idArticle')->constrained('articles', 'idArticle');
            $table->primary(['idDoc', 'idCherch', 'idArticle']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctorant_article_chercheur');
    }
};
