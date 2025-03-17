<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revues', function (Blueprint $table) {
            $table->id('idRevue');
            $table->text('nomRevue');
            $table->string('ISSN', 20)->nullable();;
            $table->string('descRevue')->nullable();
            $table->string('typeRevue', 50)->nullable();
            $table->string('editeurRevue', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revues');
    }
};
