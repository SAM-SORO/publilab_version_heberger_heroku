<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Appliquer la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chercheur_grade', function (Blueprint $table) {
            // Rendre la colonne 'dateGrade' nullable
            $table->date('dateGrade')->nullable()->change();
        });
    }

    /**
     * Annuler la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chercheur_grade', function (Blueprint $table) {
            // Revenir à une colonne non nullable
            $table->date('dateGrade')->nullable(false)->change();
        });
    }
};
