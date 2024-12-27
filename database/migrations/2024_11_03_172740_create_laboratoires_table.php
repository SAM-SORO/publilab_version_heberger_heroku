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
        Schema::create('laboratoires', function (Blueprint $table) {
            $table->id('idLabo');
            $table->string('nomLabo');                        // Champ requis
            $table->string('anneeCreation')->nullable();      // Champ nullable
            $table->string('localisationLabo')->nullable();   // Champ nullable
            $table->string('adresseLabo')->nullable();        // Champ nullable
            $table->string('telLabo')->nullable();            // Champ nullable
            $table->string('faxLabo')->nullable();            // Champ nullable
            $table->string('emailLabo');                      // Champ nullable
            $table->text('descLabo')->nullable();             // Champ nullable
            $table->foreignId('idUMRI')->constrained('umris', 'idUMRI'); // Clé étrangère non nullable
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoires');
    }
};
