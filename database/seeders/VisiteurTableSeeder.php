<?php

namespace Database\Seeders;

use App\Models\Visiteur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VisiteurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visiteur = new Visiteur();
        $visiteur->nom = "silue";
        $visiteur->email = "samuelCaleb@gmai.com";
        $visiteur->password = Hash::make("123456789");
        $visiteur->save();
    }
}
