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
        Schema::create('doctorants', function (Blueprint $table) {
            $table->id('idDoc');
            $table->string('nomDoc');
            $table->string('prenomDoc');
            $table->foreignId('idTheme')->constrained('themes','idTheme');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctorants');
    }
};
