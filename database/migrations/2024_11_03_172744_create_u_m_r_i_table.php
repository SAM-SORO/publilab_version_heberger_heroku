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
            $table->id('idUMRI');
            $table->string('sigleUMRI', 10);
            $table->string('nomUMRI', 100);
            $table->string('localisationUMRI', 30)->nullable();
            $table->unsignedBigInteger('idDirecteurUMRI')->nullable();
            $table->string('secretaireUMRI', 100)->nullable();
            $table->string('contactSecretariatUMRI', 30)->nullable();
            $table->string('emailSecretariatUMRI', 100)->nullable();
            $table->foreignId('idEDP')->constrained('edps', 'idEDP');
            $table->foreign('idDirecteurUMRI')->references('idCherch')->on('chercheurs')->onDelete('set null');
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


