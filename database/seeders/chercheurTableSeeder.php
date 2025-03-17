<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Chercheur;
use Carbon\Carbon;

class ChercheurTableSeeder extends Seeder
{
    public function run()
    {
        Chercheur::create([
            'nomCherch' => 'Sam',
            'prenomCherch' => 'Samuel',
            'genreCherch' => 'M',
            'matriculeCherch' => 'MAT12345',
            'password' => Hash::make('12345678'),
            'emploiCherch' => 'Chercheur en mathématiques',
            'departementCherch' => 'Sciences et Technologies',
            'fonctionAdministrativeCherch' => 'Responsable de laboratoire',
            'specialiteCherch' => 'Mathématiques',
            'emailCherch' => 'samsoro@inphb.ci',
            'dateNaissCherch' => Carbon::parse('1990-05-15'),
            'dateArriveeCherch' => now()->toDateString(),
            'telCherch' => '0565320650',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
