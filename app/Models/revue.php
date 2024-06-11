<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revue extends Model
{
    use HasFactory;

    protected $fillable = [
        'cod_ISSN', 'cod_DOI', 'editeur', 'titre', 'indexe', 'organisme_index'
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'contenir', 'num_rev', 'num_art');
    }
}
