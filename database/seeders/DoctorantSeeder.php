<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctorant;
use Carbon\Carbon;

class DoctorantSeeder extends Seeder
{
    public function run()
    {
        Doctorant::create([
            'nomDoc' => 'Kouassi',
            'prenomDoc' => 'Jean-Pierre',
            'genreDoc' => 'M',
            'matriculeDoc' => 'DOC2024001',
            'password' => Hash::make('password123'),
            'emailDoc' => 'jeanpierre.kouassi@univ-ci.ci',
            'telDoc' => '0756321458',
            'idTheme' => null, // Peut être mis à jour avec une clé valide existante
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
