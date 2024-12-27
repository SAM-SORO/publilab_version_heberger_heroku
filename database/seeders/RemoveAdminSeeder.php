<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RemoveAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Admin::where('email', 'admin@gmail.com')->delete();
        // Ajoutez d'autres instructions pour supprimer d'autres données insérées
    }



}
