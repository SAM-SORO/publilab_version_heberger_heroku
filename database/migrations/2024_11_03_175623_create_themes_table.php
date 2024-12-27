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
        Schema::create('themes', function (Blueprint $table) {
            $table->id('idTheme');
            $table->text('descTheme');
            $table->foreignId('idAxeRech')->constrained('axe_recherches', 'idAxeRech');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};


// Schema::create('themes', function (Blueprint $table) {
//     $table->id('idTheme');
//     $table->string('intituleTheme'); // Intitulé du thème
//     $table->text('descTheme')->nullable(); // Description du thème (nullable)
//     $table->foreignId('idAxeRech')->constrained('axe_recherches', 'idAxeRech');  // Clé étrangère
//     $table->timestamps();
// });
