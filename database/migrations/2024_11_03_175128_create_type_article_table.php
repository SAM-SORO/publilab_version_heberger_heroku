<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_articles', function (Blueprint $table) {
            $table->id('idTypeArticle');
            $table->string('nomTypeArticle', 100)->unique();
            $table->string('descTypeArticle')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_articles');
    }
};
