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
        Schema::table('chercheurs', function (Blueprint $table) {
            $table->foreign('idUMRI')->references('idUMRI')->on('umris')->onDelete('cascade');
            $table->foreign('idLabo')->references('idLabo')->on('laboratoires')->onDelete('set null');
        });

    }

    public function down(): void
    {
        Schema::table('chercheurs', function (Blueprint $table) {
            $table->dropForeign(['idUMRI']);
            $table->dropForeign(['idLabo']);
        });
    }

};


// php artisan make:migration add_foreign_keys_to_chercheurs_table
