<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctorants', function (Blueprint $table) {
            $table->id('idDoc');
            $table->string('nomDoc', 30);
            $table->string('prenomDoc', 100)->nullable();
            $table->enum('genreDoc', ['M', 'F'])->nullable();
            $table->string('matriculeDoc', 20)->nullable();
            $table->string('password');
            $table->string('emailDoc', 100)->nullable();
            $table->string('telDoc', 15)->nullable();
            $table->foreignId('idTheme')->nullable()->constrained('themes', 'idTheme')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctorants');
    }
};
