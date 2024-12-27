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
        Schema::create('axe_recherches', function (Blueprint $table) {
            $table->id('idAxeRech');
            $table->string('titreAxeRech');              // Champ requis
            $table->text('descAxeRech')->nullable();      // Champ nullable
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('axe_recherches');
    }
};
