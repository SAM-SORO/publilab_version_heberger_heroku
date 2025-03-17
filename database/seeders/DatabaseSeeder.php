<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AdminTableSeeder::class,
            ChercheurTableSeeder::class,
            DoctorantSeeder::class,
            // VisiteurTableSeeder::class,
            // EdpTableSeeder::class,
            // UmriTableSeeder::class,
            //LaboratoireTableSeeder::class,
            //ArticleTableSeeder::class,
            //ChercheurArticleSeeder::class,
            //RevueSeeder::class,
            //BdIndexationRevueSeeder::class,
        ]);
    }
}

//ce dossier est creer afin d'executer nos seeder simplement avec la commande php artisan db:seed

// pour ne pas avoir a faire php artisan db:seed --class = seederName pour executer un a un
