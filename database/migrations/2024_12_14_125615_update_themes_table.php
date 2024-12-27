<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            // Ajouter la colonne `intituleTheme` (requise)
            $table->string('intituleTheme')->after('idTheme');

            // Modifier `descTheme` pour qu'elle soit nullable
            $table->text('descTheme')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            // Supprimer la colonne `intituleTheme`
            $table->dropColumn('intituleTheme');

            // Revenir à une colonne non-nullable pour `descTheme`
            $table->text('descTheme')->nullable(false)->change();
        });
    }
};
