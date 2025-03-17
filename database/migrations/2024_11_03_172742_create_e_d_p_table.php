<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edps', function (Blueprint $table) {
            $table->id('idEDP');
            $table->string('sigleEDP', 10);
            $table->string('nomEDP', 100);
            $table->string('localisationEDP', 30)->nullable();
            $table->unsignedBigInteger('idDirecteurEDP')->nullable();
            $table->string('secretaireEDP', 100)->nullable();
            $table->string('contactSecretariatEDP', 100)->nullable();
            $table->string('emailSecretariatEDP', 100)->nullable();
            $table->foreign('idDirecteurEDP')->references('idCherch')->on('chercheurs')->onDelete('set null');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edps');
    }
};
