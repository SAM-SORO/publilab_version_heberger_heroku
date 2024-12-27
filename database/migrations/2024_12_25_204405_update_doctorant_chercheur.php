<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctorant_chercheur', function (Blueprint $table) {
            // Rendre 'dateDebut' nullable
            $table->date('dateDebut')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('doctorant_chercheur', function (Blueprint $table) {
            // Revenir en arrière et rendre 'dateDebut' non nullable
            $table->date('dateDebut')->nullable(false)->change();
        });
    }
};
