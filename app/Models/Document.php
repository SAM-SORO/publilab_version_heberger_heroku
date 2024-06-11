<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'format', 'lien', 'image', 'num_art'
    ];

    // Définir la relation avec le modèle Article
    public function article()
    {
        return $this->belongsTo(Article::class, 'num_art', 'id');
    }
}
