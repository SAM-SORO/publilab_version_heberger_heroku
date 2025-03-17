<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id('idArticle');
            $table->text('titreArticle');
            $table->text('lienArticle')->nullable();
            $table->text('doi')->nullable();
            $table->text('resumeArticle')->nullable();
            $table->integer('numero')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('pageDebut')->nullable();
            $table->integer('pageFin')->nullable();
            $table->date('datePubArt')->nullable();
            $table->foreignId('idPub')->nullable()->constrained('publications', 'idPub');
            $table->foreignId('idTypeArticle')->nullable()->constrained('type_articles', 'idTypeArticle')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
