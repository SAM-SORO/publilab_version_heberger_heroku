<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bdindexation_revue', function (Blueprint $table) {
            $table->date('dateDebut')->nullable()->change(); // Rendre dateDebut nullable
            $table->date('dateFin')->nullable()->change();   // Rendre dateFin nullable
        });
    }

    public function down()
    {
        Schema::table('bdindexation_revue', function (Blueprint $table) {
            $table->date('dateDebut')->nullable(false)->change(); // Restaurer dateDebut comme non nullable
            $table->date('dateFin')->nullable(false)->change();   // Restaurer dateFin comme non nullable
        });
    }
};

// php artisan make:migration update_bdindexation_revue_dates --table=bdindexation_revue
