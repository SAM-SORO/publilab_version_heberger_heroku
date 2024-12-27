<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laboratoire_axe_recherche', function (Blueprint $table) {
            $table->foreignId('idLabo')->constrained('laboratoires', 'idLabo');
            $table->foreignId('idAxeRech')->constrained('axe_recherches', 'idAxeRech');
            $table->primary(['idLabo', 'idAxeRech']);  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoire_axe_recherches');
    }
};
