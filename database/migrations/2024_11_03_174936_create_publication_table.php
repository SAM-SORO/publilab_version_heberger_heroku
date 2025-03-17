<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id('idPub');
            $table->string('titrePub', 255);
            $table->string('descPub')->nullable();
            $table->string('ISSN')->nullable();
            $table->string('editeurPub', 200)->nullable();
            $table->foreignId('idTypePub')->nullable()->constrained('type_publications', 'idTypePub')
            ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
