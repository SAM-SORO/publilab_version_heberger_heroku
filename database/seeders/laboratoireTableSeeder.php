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

        $laboratoire = new laboratoire();
        $laboratoire->nom = "Chimaster";
        $laboratoire->description = "laboratoire de recherche de l'INPHB";
        $laboratoire->adresse = "274 INPHB-CENTRE";
        $laboratoire->save();

        $laboratoire = new laboratoire();
        $laboratoire->nom = "Centronik";
        $laboratoire->description = "laboratoire électronique de l'INPHB";
        $laboratoire->adresse = "245 INPHB-CENTRE";
        $laboratoire->save();

        $laboratoire = new laboratoire();
        $laboratoire->nom = "INFO-lab";
        $laboratoire->description = "laboratoire informatique de l'INPHB";
        $laboratoire->adresse = "332 INPHB-CENTRE";

        $laboratoire->save();

        $laboratoire = new laboratoire();
        $laboratoire->nom = "Y'ELLO-LAB";
        $laboratoire->description = "laboratoire de fabrication numerique de l'INPHB";
        $laboratoire->adresse = "223 INPHB-CENTRE";
        $laboratoire->save();

    }
}
