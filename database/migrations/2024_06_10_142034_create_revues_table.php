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
            $table->id();
            $table->string('cod_ISSN');
            $table->string('cod_DOI');
            $table->string('editeur')->nullable();
            $table->string('titre')->nullable();
            $table->boolean('indexe')->nullable();
            $table->string('organisme_index')->nullable();
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

