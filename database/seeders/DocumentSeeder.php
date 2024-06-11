<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $document = new Document();
        $document->format = "pdf";
        $document->lien = null;
        $document->image = null;
        $document->num_art = 2;
        $document->created_at = date('2023');
        $document->save();
    }
}
