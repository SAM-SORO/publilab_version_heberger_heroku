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
        $laboratoire->nom_labo = "Chimaster";
        $laboratoire->desc_labo = "laboratoire de recherche de l'INPHB";
        $laboratoire->adr_labo = "274 INPHB-CENTRE";
        $laboratoire->save();

        $laboratoire = new laboratoire();
        $laboratoire->nom_labo = "Centronik";
        $laboratoire->desc_labo = "laboratoire électronique de l'INPHB";
        $laboratoire->adr_labo = "245 INPHB-CENTRE";
        $laboratoire->save();

        $laboratoire = new laboratoire();
        $laboratoire->nom_labo = "INFO-lab";
        $laboratoire->desc_labo = "laboratoire informatique de l'INPHB";
        $laboratoire->adr_labo = "332 INPHB-CENTRE";

        $laboratoire->save();

        $laboratoire = new laboratoire();
        $laboratoire->nom_labo = "Y'ELLO-LAB";
        $laboratoire->desc_labo = "laboratoire de fabrication numerique de l'INPHB";
        $laboratoire->adr_labo = "223 INPHB-CENTRE";
        $laboratoire->save();

    }
}
