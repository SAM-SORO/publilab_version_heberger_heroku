<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chercheur_grade', function (Blueprint $table) {
            $table->foreignId('idCherch')->constrained('chercheurs', 'idCherch')->onDelete('cascade');
            $table->foreignId('idGrade')->constrained('grades', 'idGrade')->onDelete('cascade');
            $table->date('dateGrade')->nullable();
            $table->primary(['idCherch', 'idGrade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chercheur_grade');
    }
};
