<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laboratoires', function (Blueprint $table) {
            $table->id('idLabo');
            $table->string('sigleLabo', 20);
            $table->string('nomLabo', 100);
            $table->string('anneeCreation', 5)->nullable();
            $table->string('localisationLabo', 50)->nullable();
            $table->string('adresseLabo', 100)->nullable();
            $table->string('telLabo', 30)->nullable();
            $table->string('faxLabo', 30)->nullable();
            $table->string('emailLabo', 100)->nullable();
            $table->text('descLabo')->nullable();
            $table->unsignedBigInteger('idDirecteurLabo')->nullable();
            $table->foreign('idDirecteurLabo')->references('idCherch')->on('chercheurs')->onDelete('set null');
            $table->foreignId('idUMRI')->nullable()->constrained('umris', 'idUMRI')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratoires');
    }
};
