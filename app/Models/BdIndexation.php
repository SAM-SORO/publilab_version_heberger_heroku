<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdIndexation extends Model
{
    use HasFactory;

    protected $table = 'bd_indexations';
    protected $primaryKey = 'idBDIndex';

    protected $fillable = [
        'nomBDInd'
    ];

    /**
     * Relation avec les publications
     * Une base d'indexation peut indexer plusieurs publications,
     * et une publication peut être indexée dans plusieurs bases
     */
    public function publications()
    {
        return $this->belongsToMany(
            Publication::class,
            'publication_bdindexation',
            'idBDIndex',
            'idPub'
        )
        ->withPivot(['dateDebut', 'dateFin'])
        ->withTimestamps();
    }

    /**
     * Obtenir les publications actuellement indexées
     * (celles dont la dateFin n'est pas définie ou future)
     */
    public function getPublicationsActives()
    {
        return $this->publications()
            ->where(function($query) {
                $query->wherePivot('dateFin', null)
                      ->orWhere('dateFin', '>=', now());
            })
            ->get();
    }

    /**
     * Obtenir le nombre de publications indexées
     */
    public function getPublicationsCount()
    {
        return $this->publications()->count();
    }

    /**
     * Vérifie si la base d'indexation a des publications
     */
    public function hasPublications()
    {
        return $this->publications()->exists();
    }

    /**
     * Obtenir les publications indexées à une date donnée
     */
    public function getPublicationsByDate($date)
    {
        return $this->publications()
            ->wherePivot('dateDebut', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->wherePivot('dateFin', '>=', $date)
                      ->orWherePivot('dateFin', null);
            })
            ->get();
    }
}



// php artisan make:model BdIndexation -m
