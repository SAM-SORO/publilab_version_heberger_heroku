<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeArticle extends Model
{
    use HasFactory;

    protected $table = 'type_articles';
    protected $primaryKey = 'idTypeArticle';

    protected $fillable = [
        'nomTypeArticle',
        'descTypeArticle'
    ];

    protected $casts = [
        'nomTypeArticle' => 'string',
        'descTypeArticle' => 'string'
    ];

    /**
     * Relation avec Article (one-to-many)
     * Un type d'article peut être associé à plusieurs articles
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'idTypeArticle');
    }

    /**
     * Obtenir les articles par année
     */
    public function getArticlesByYear($year)
    {
        return $this->articles()
            ->whereYear('datePubArt', $year)
            ->orderBy('datePubArt', 'desc')
            ->get();
    }

    /**
     * Obtenir les articles les plus récents
     */
    public function getRecentArticles($limit = 5)
    {
        return $this->articles()
            ->orderBy('datePubArt', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Obtenir les articles par publication
     */
    public function getArticlesByPublication($idPub)
    {
        return $this->articles()
            ->where('idPub', $idPub)
            ->orderBy('datePubArt', 'desc')
            ->get();
    }

    /**
     * Obtenir le nombre d'articles par année
     */
    public function getArticlesCountByYear($year)
    {
        return $this->articles()
            ->whereYear('datePubArt', $year)
            ->count();
    }

    /**
     * Obtenir le nombre d'articles de ce type
     */
    public function getArticlesCount()
    {
        return $this->articles()->count();
    }
}
