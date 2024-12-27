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
        Schema::create('umris', function (Blueprint $table) {
            $table->id('idUMRI');                     // Clé primaire auto-incrémentée
            $table->string('nomUMRI');                // Champ requis
            $table->string('localisationUMI')->nullable(); // Champ nullable
            $table->string('WhatsAppUMRI')->nullable();    // Champ nullable
            $table->string('emailUMRI')->nullable();       // Champ nullable
            $table->foreignId('idEDP')->constrained('edps', 'idEDP'); // Clé étrangère
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umris');
    }
};


