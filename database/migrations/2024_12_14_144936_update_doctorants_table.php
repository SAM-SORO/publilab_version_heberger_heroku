<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctorants', function (Blueprint $table) {
            // Rendre prenomDoc nullable
            $table->string('prenomDoc')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('doctorants', function (Blueprint $table) {
            // Si on veut revenir en arrière, on annule la modification
            $table->string('prenomDoc')->nullable(false)->change();
        });
    }
};
