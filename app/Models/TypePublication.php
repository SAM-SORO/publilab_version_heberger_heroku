<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePublication extends Model
{
    use HasFactory;

    protected $table = 'type_publications';
    protected $primaryKey = 'idTypePub';

    protected $fillable = [
        'libeleTypePub',
        'descTypePub'
    ];

    protected $casts = [
        'libeleTypePub' => 'string',
        'descTypePub' => 'string'
    ];

    /**
     * Relation avec Publication (one-to-many)
     * Un type de publication peut être associé à plusieurs publications
     */
    public function publications()
    {
        return $this->hasMany(Publication::class, 'idTypePub');
    }


    /**
     * Obtenir les publications par année
     */
    public function getPublicationsByYear($year)
    {
        return $this->publications()
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir les publications les plus récentes
     */
    public function getRecentPublications($limit = 5)
    {
        return $this->publications()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }


    /**
     * Obtenir le nombre de publications par année
     */
    public function getPublicationsCountByYear($year)
    {
        return $this->publications()
            ->whereYear('created_at', $year)
            ->count();
    }


}
