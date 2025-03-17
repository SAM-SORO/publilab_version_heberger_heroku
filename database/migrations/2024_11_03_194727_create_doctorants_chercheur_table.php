<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctorant_chercheur', function (Blueprint $table) {
            $table->foreignId('idDoc')->constrained('doctorants', 'idDoc')->onDelete('cascade');
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch')->onDelete('cascade');
            $table->integer('niveau')->nullable();
            $table->date('dateDebut')->nullable();
            $table->date('dateFin')->nullable();
            $table->primary(['idDoc', 'idCherch']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctorant_chercheur');
    }
};
