<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('article_revue', function (Blueprint $table) {
            $table->integer('pageDebut')->nullable()->change();
            $table->integer('pageFin')->nullable()->change();
            $table->date('datePubArt')->nullable()->change();                                             // Champ requis pour la date de publication
        });
    }

    public function down()
    {
        Schema::table('article_revue', function (Blueprint $table) {
            $table->integer('pageDebut')->nullable(false)->change();
            $table->integer('pageFin')->nullable(false)->change();
            $table->date('datePubArt')->nullable(false)->change();                                             // Champ requis pour la date de publication


        });
    }
};
