<?php

namespace Database\Seeders;
use App\Models\Edp; // Assure-toi d'importer le modèle EDP

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EdpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Edp::create([
            'nomEDP' => 'CAC', // Remplace par le nom souhaité
            'localisationEDP' => 'INPHB', // Remplace par la localisation souhaitée
            'WhatsAppUMI' => '1234567890', // Remplace par le numéro WhatsApp souhaité
            'emailUMI' => 'edp@exemple.com', // Remplace par l'email souhaité
        ]);
    }
}
