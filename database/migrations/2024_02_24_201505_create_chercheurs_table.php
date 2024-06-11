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
        Schema::create('chercheurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_labo');
            $table->foreign('id_labo')->references('id')->on('laboratoires')->onDelete('cascade');
            $table->string("nom");
            $table->string("prenom");
            $table->string("email")->unique();
            $table->string("contact");
            $table->string('photo')->nullable();
            $table->string("password");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chercheurs');
    }
};
