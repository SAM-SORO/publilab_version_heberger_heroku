<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chercheur_laboratoire', function (Blueprint $table) {
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch')->onDelete('cascade');
            $table->foreignId('idLabo')->constrained('laboratoires', 'idLabo')->onDelete('cascade');
            $table->integer('niveau')->nullable();
            $table->date('dateAffectation')->nullable();
            $table->primary(['idCherch', 'idLabo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chercheur_laboratoire');
    }
};
