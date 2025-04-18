<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_publications', function (Blueprint $table) {
            $table->id('idTypePub');
            $table->string('libeleTypePub', 50)->nullable();
            $table->string('descTypePub')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_publications');
    }
};
