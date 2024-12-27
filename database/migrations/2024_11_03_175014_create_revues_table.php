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
        Schema::create('revues', function (Blueprint $table) {
            $table->id('idRevue');
            $table->string('ISSN');                  // Champ requis
            $table->string('nomRevue');              // Champ requis
            $table->text('descRevue')->nullable();   // Champ nullable
            $table->string('typeRevue')->nullable();             // Champ requis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revues');
    }
};
