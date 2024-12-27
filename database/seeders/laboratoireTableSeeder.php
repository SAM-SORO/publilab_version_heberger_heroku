<?php

namespace Database\Seeders;

use App\Models\laboratoire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class laboratoireTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $laboratoire = new Laboratoire(); // Notez la capitalisation pour le modèle
        $laboratoire->nomLabo = "INFO-lab"; // Utilisez le bon nom de champ
        $laboratoire->anneeCreation = "2020"; // Exemple de valeur, ajustez si nécessaire
        $laboratoire->localisationLabo = "Abidjan"; // Exemple de valeur, ajustez si nécessaire
        $laboratoire->adresseLabo = "332 INPHB-CENTRE"; // Utilisez le bon nom de champ
        $laboratoire->telLabo = "0123456789"; // Exemple de numéro de téléphone
        $laboratoire->faxLabo = "0123456789"; // Exemple de numéro de fax
        $laboratoire->emailLabo = "info-lab@inphb.ci"; // Exemple d'email
        $laboratoire->descLabo = "Laboratoire informatique de l'INPHB"; // Utilisez le bon nom de champ
        $laboratoire->idUMRI = 1; // Assurez-vous que l'idUMRI existe dans la table umris
        $laboratoire->save();

    }
}
