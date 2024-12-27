<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChercheurArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Insérer des données dans la table `chercheur_article`
        DB::table('chercheur_article')->insert([
        // Assurez-vous que les IDs d'article et de chercheur existent
        [
            'idCherch' => 1, // Remplacez par l'ID du chercheur
            'idArticle' => 1, // Remplacez par l'ID de l'article
        ],

    ]);
    }
}
