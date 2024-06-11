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
        Schema::create('contenir', function (Blueprint $table) {
            $table->unsignedBigInteger('num_art');
            $table->unsignedBigInteger('num_rev');
            $table->string('PageDebut')->nullable();
            $table->string('PageFin')->nullable();
            $table->string('DatePublication')->nullable();
            $table->string('Volume')->nullable();
            $table->string('Numero')->nullable();
            $table->timestamps();

            $table->foreign('num_art')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('num_rev')->references('id')->on('revues')->onDelete('cascade');

            $table->primary(['num_art', 'num_rev']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenir');
    }
};
