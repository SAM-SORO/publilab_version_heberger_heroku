<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chercheurs', function (Blueprint $table) {
            // Modifier les colonnes pour qu'elles soient nullable et garder la contrainte unique pour l'email
            $table->string('prenomCherch')->nullable()->change();
            $table->string('telCherch')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('chercheurs', function (Blueprint $table) {
            // Revert les colonnes à non nullable et unique
            $table->string('prenomCherch')->nullable(false)->change();
            $table->string('telCherch')->nullable(false)->change();
            $table->string('emailCherch')->nullable(false)->unique()->change(); // Email non nullable et unique
        });
    }
};
