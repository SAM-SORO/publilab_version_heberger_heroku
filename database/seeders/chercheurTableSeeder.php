<?php

namespace Database\Seeders;

use App\Models\Chercheur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChercheurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $chercheur = new Chercheur();
        $chercheur->nomCherch = "soro";
        $chercheur->prenomCherch = "samuel";
        $chercheur->adresse = "123 INPHB-CENTRE"; // Champ nullable
        $chercheur->telCherch = "0546829308"; // Champ nullable
        $chercheur->emailCherch = "samuel.soro@inphb.ci";
        $chercheur->password = Hash::make("123456789");
        $chercheur->specialite = "Biologie"; // Champ nullable
        $chercheur->idLabo = 1; // Assurez-vous que l'idLabo existe dans la table laboratoires
        $chercheur->dateArrivee = now()->toDateString(); // Champ nullable, exemple de date d'arrivée
        $chercheur->save();



        $chercheur = new Chercheur();
        $chercheur->nomCherch = "sam";
        $chercheur->prenomCherch = "samuel";
        $chercheur->adresse = "123 INPHB-CENTRE"; // Champ nullable
        $chercheur->telCherch = "0565320650"; // Champ nullable
        $chercheur->emailCherch = "samsoro@inphb.ci";
        $chercheur->password = Hash::make("12345678");
        $chercheur->specialite = "Math"; // Champ nullable
        $chercheur->idLabo = 1; // Assurez-vous que l'idLabo existe dans la table laboratoires
        $chercheur->dateArrivee = now()->toDateString(); // Champ nullable, exemple de date d'arrivée
        $chercheur->save();
    }
}
