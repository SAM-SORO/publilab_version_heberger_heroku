<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('revues', function (Blueprint $table) {
            $table->string('ISSN')->nullable()->change(); // Rendre le champ ISSN nullable
        });
    }

    public function down()
    {
        Schema::table('revues', function (Blueprint $table) {
            $table->string('ISSN')->nullable(false)->change(); // Restaurer le champ comme non nullable
        });
    }
};

// php artisan make:migration update_revues_fields --table=revues
