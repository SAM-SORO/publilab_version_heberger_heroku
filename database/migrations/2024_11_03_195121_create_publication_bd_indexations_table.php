<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_bdindexation', function (Blueprint $table) {

            $table->foreignId('idBDIndex')
                  ->references('idBDIndex')
                  ->on('bd_indexations')
                  ->onDelete('cascade');

            $table->foreignId('idPub')
                  ->references('idPub')
                  ->on('publications')
                  ->onDelete('cascade');

            $table->date('dateDebut')->nullable();
            $table->date('dateFin')->nullable();
            $table->primary(['idBDIndex', 'idPub']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_bdindexation');
    }
};
