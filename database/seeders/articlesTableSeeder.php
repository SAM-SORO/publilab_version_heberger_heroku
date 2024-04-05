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
    public function run(): void
    {
        //
        $article = new article();
        $article->id_ch = 1;
        $article->titre_art = "Les methodes d'analyse PU/UML et MERISE";
        $article->desc_art = "soro samuel cdnsqlckd";
        $article->save();

        $article = new article();
        $article->id_ch = 2;
        $article->titre_art = "Cyber securité";
        $article->desc_art = "silue caleb dqndksjq";
        $article->save();

        $article = new article();
        $article->id_ch = 1;
        $article->titre_art = "parlon D'IA";
        $article->desc_art = " j,iok";
        $article->save();
    }
}
