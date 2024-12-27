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
        Schema::create('chercheur_article', function (Blueprint $table) {
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch');
            $table->foreignId('idArticle')->constrained('articles', 'idArticle');
            $table->primary(['idCherch', 'idArticle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chercheur_articles');
    }
};
