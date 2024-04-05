<?php

namespace Database\Seeders;


use App\Models\chercheurs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class chercheurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $chercheur = new chercheurs();
        $chercheur->id_labo = 1;
        $chercheur->nom_ch = "anon";
        $chercheur->prenom_ch = "joseph";
        $chercheur->email_ch = "anon.joseph@inphb.ci";
        $chercheur->contact_ch = "0701020304";
        $chercheur->password_ch = "anonjoseph";
        $chercheur->save();

        $chercheur = new chercheurs();
        $chercheur->id_labo = 1;
        $chercheur->nom_ch = "soro";
        $chercheur->prenom_ch = "samuel";
        $chercheur->email_ch = "samuel.soro@inphb.ci";
        $chercheur->contact_ch = "0102030405";
        $chercheur->password_ch = "sorosamuel";
        $chercheur->save();

    }
}
