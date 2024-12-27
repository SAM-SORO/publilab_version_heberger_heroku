<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Revue;
use App\Models\Article;

class RevueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer une revue
        $revue = new Revue();
        $revue->ISSN = "1234-5678"; // Exemple d'ISSN
        $revue->nomRevue = "Journal of Programming"; // Nom de la revue
        $revue->descRevue = "Une revue scientifique sur la programmation."; // Description (optionnelle)
        $revue->typeRevue = "Scientifique"; // Type de revue
        $revue->save();

        // Récupérer tous les articles existants
        $articles = Article::all();

        // Attacher tous les articles à cette revue
        foreach ($articles as $article) {
            $article->revues()->attach($revue->idRevue, [
                'datePubArt' => now(),  // Date de publication
                'volume' => '10',       // Exemple de volume
                'pageDebut' => 100,     // Exemple de page début
                'pageFin' => 110,        // Exemple de page fin
                'numero' => 10,
            ]);
        }

        // Message de confirmation
        $this->command->info('Tous les articles ont été attachés à la revue ' . $revue->nomRevue);
    }
}


// php artisan db:seed --class=RevueSeeder
