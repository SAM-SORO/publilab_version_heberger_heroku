<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revue extends Model
{
    use HasFactory;

    protected $table = 'revues';
    protected $primaryKey = 'idRevue';

    protected $fillable = [
        'nomRevue',
        'ISSN',
        'descRevue',
        'typeRevue',
        'editeurRevue'
    ];

    protected $casts = [
        'ISSN' => 'string'
    ];

    // Relation avec les bases d'indexation
    public function bdIndexations()
    {
        return $this->belongsToMany(
            BdIndexation::class,
            'bdindexation_revue',
            'idRevue',
            'idBDInd'
        )
        ->withPivot(['dateDebut', 'dateFin'])
        ->withTimestamps();
    }

    // Relation avec les articles
    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_revue',
            'idRevue',
            'idArticle'
        )
        ->withPivot(['datePubArt', 'volume', 'numero', 'pageDebut', 'pageFin'])
        ->withTimestamps();
    }

    /**
     * Vérifie si la revue est indexée dans une base de données
     */
    public function isIndexed()
    {
        return $this->bdIndexations()->exists();
    }

    /**
     * Vérifie si la revue a des articles publiés
     */
    public function hasArticles()
    {
        return $this->articles()->exists();
    }

    /**
     * Obtenir le nombre total d'articles publiés
     */
    public function getArticlesCount()
    {
        return $this->articles()->count();
    }

    /**
     * Obtenir les articles publiés dans un volume spécifique
     */
    public function getArticlesByVolume($volume)
    {
        return $this->articles()
            ->wherePivot('volume', $volume)
            ->get();
    }
}

