<?php

namespace Database\Seeders;

use App\Models\article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class articlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {


        $article = new article();
        $article->id_ch = 2;
        $article->titre = "Cyber securité";
        $article->description = "silue caleb dqndksjq";
        $article->save();

        $article = new article();
        $article->id_ch = 1;
        $article->titre = "parlon D'IA";
        $article->description = " jiok";
        $article->save();
    }
}
