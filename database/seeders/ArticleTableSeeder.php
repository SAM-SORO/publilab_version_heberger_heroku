<?php

namespace Database\Seeders;

use App\Models\Article; // Assurez-vous que le modèle Article est bien importé

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un nouvel article
        $article = new Article();
        $article->doi = "10.1234/abcd.efgh"; // Exemple de DOI, peut être null
        $article->titreArticle = "Introduction à la Programmation"; // Champ requis
        $article->resumeArticle = "Cet article introduit les concepts de base de la programmation."; // Champ nullable
        $article->save();

    }
}


