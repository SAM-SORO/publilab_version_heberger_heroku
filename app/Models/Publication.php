<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $table = 'publications';
    protected $primaryKey = 'idPub';

    protected $fillable = [
        'titrePub',
        'descPub',
        'ISSN',
        'editeurPub',
        'idTypePub'
    ];

    protected $casts = [
        'ISSN' => 'string',
        'titrePub' => 'string',
        'descPub' => 'string',
        'editeurPub' => 'string'
    ];

    /**
     * Relation avec TypePublication (belongsTo)
     * Une publication appartient à un type de publication
     */
    public function typePublication()
    {
        return $this->belongsTo(TypePublication::class, 'idTypePub');
    }

    /**
     * Relation avec les articles (one-to-many)
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'idPub');
    }

    /**
     * Relation avec les bases d'indexation (many-to-many)
     */
    public function bdIndexations()
    {
        return $this->belongsToMany(
            BdIndexation::class,
            'publication_bdindexation',
            'idPub',
            'idBDIndex'
        )->withPivot('dateDebut', 'dateFin')
        ->withTimestamps();
    }

    /**
     * Obtenir les bases d'indexation actives
     */
    public function getBdIndexationsActives()
    {
        return $this->bdIndexations()
            ->wherePivot('dateFin', null)
            ->orWhere('dateFin', '>=', now())
            ->get();
    }

    /**
     * Vérifier si la publication est indexée dans une base donnée
     */
    public function isIndexedIn($idBDInd)
    {
        return $this->bdIndexations()
            ->where('idBDIndex', $idBDInd)
            ->wherePivot('dateFin', null)
            ->orWhere('dateFin', '>=', now())
            ->exists();
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
     * Obtenir les articles récents
     */
    public function getRecentArticles($limit = 5)
    {
        return $this->articles()
            ->orderBy('datePubArt', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Obtenir le nombre d'articles
     */
    public function getArticlesCount()
    {
        return $this->articles()->count();
    }

}
