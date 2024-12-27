<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Revue;
use App\Models\BdIndexation;

class BdIndexationRevueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer une base d'indexation
        $bdIndexation = new BdIndexation();
        $bdIndexation->nomBDInd = "Scopus"; // Nom de la base d'indexation
        $bdIndexation->save();

        // Trouver ou créer la revue
        $revue = Revue::firstOrCreate([
            'ISSN' => "1234-5678"
        ], [
            'nomRevue' => "Journal of Programming",
            'descRevue' => "Une revue scientifique sur la programmation.",
            'typeRevue' => "Scientifique"
        ]);

        // Associer la base d'indexation à la revue via la table pivot avec des dates
        $revue->bdIndexations()->attach($bdIndexation->idBDIndex, [
            'dateDebut' => now()->subYear(2),  // Exemple de date de début il y a 2 ans
            'dateFin' => now(),                // Exemple de date de fin (aujourd'hui)
        ]);
    }
}
