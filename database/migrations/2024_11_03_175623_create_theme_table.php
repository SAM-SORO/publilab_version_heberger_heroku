<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id('idTheme');
            $table->text('intituleTheme')->nullable();
            $table->text('descTheme')->nullable();
            $table->boolean('etatAttribution')->default(false);
            $table->foreignId('idAxeRech')->nullable()->constrained('axe_recherches', 'idAxeRech')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
