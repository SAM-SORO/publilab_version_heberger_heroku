<?php

namespace Database\Seeders;

use App\Models\UMRI;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UmriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UMRI::create([
            'nomUMRI' => 'Nom de l\'UMRI', // Remplace par le nom souhaité
            'localisationUMI' => 'Localisation de l\'UMRI', // Remplace par la localisation souhaitée
            'WhatsAppUMRI' => '0987654321', // Remplace par le numéro WhatsApp souhaité
            'emailUMRI' => 'email@exemple.com', // Remplace par l'email souhaité
            'idEDP' => 1, // Assure-toi que l'EDP avec cet ID existe déjà
        ]);
    }
}
