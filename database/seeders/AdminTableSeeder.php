<?php

namespace Database\Seeders;

use App\Models\Admin; // Assure-toi d'importer le modèle Admin

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'nom' => 'soro', // Remplace par le nom souhaité
            'email' => 'soro@gmail.com', // Remplace par l'email souhaité
            'password' => Hash::make('sorosamuel'), // Remplace par le mot de passe souhaité
        ]);
    }
}
