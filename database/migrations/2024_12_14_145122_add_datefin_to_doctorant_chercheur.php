<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctorant_chercheur', function (Blueprint $table) {
            // Ajouter la colonne dateFin qui peut être nulle
            $table->date('dateFin')->nullable()->after('dateDebut');
        });
    }

    public function down()
    {
        Schema::table('doctorant_chercheur', function (Blueprint $table) {
            // Si on veut revenir en arrière, on supprime la colonne dateFin
            $table->dropColumn('dateFin');
        });
    }
};
