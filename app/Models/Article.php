<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_ch', 'titre', 'description'
    ];

    public function chercheur()
    {
        return $this->belongsTo(Chercheur::class, 'id_ch');
    }

    // Définir la relation avec le modèle Document
    public function documents()
    {
        return $this->hasMany(Document::class, 'num_art', 'id');
    }

    public function revues()
    {
        return $this->belongsToMany(Revue::class, 'contenir', 'num_art', 'num_rev');
    }
}

