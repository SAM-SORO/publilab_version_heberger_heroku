<?php

namespace Database\Seeders;

use App\Models\Chercheur;
use App\Models\chercheurs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class chercheurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //
        $chercheur = new Chercheur();
        $chercheur->id_labo = 1;
        $chercheur->nom = "anon";
        $chercheur->prenom = "joseph";
        $chercheur->email = "anon.joseph@inphb.ci";
        $chercheur->contact = "0701020304";
        $chercheur->password = Hash::make("123456789");
        $chercheur->save();

        $chercheur = new chercheur();
        $chercheur->id_labo = 2;
        $chercheur->nom = "soro";
        $chercheur->prenom = "samuel";
        $chercheur->email = "samuel.soro@inphb.ci";
        $chercheur->contact = "0546829308";
        $chercheur->password = Hash::make("123456789");
        $chercheur->save();

    }
}
