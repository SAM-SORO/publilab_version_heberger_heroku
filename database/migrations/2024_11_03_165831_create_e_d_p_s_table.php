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
        Schema::create('edps', function (Blueprint $table) {
            $table->id('idEDP');                     // Clé primaire auto-incrémentée
            $table->string('nomEDP');                // Champ requis
            $table->string('localisationEDP')->nullable(); // Champ nullable
            $table->string('WhatsAppUMI')->nullable();     // Champ nullable
            $table->string('emailUMI')->nullable();        // Champ nullable
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edps');
    }
};
