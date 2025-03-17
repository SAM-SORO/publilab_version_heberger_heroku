<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';
    protected $primaryKey = 'idGrade';

    protected $fillable = [
        'sigleGrade',
        'nomGrade'
    ];

    protected $casts = [
        'sigleGrade' => 'string',
        'nomGrade' => 'string'
    ];

    /**
     * Relation avec Chercheur (many-to-many)
     * Un grade peut être attribué à plusieurs chercheurs,
     * et un chercheur peut avoir plusieurs grades au fil du temps.
     */
    public function chercheurs()
    {
        return $this->belongsToMany(Chercheur::class, 'chercheur_grade', 'idGrade', 'idCherch')
            ->withPivot('dateGrade');
    }




    /**
     * Vérifie si un chercheur spécifique a ce grade
     */
    public function hasChercheur($idChercheur)
    {
        return $this->chercheurs()
            ->where('idCherch', $idChercheur)
            ->exists();
    }

    /**
     * Obtenir le nombre de chercheurs ayant ce grade
     */
    public function getChercheurCount()
    {
        return $this->chercheurs()->count();
    }


    /**
     * Vérifie si le grade est attribué à des chercheurs
     */
    public function isUsed()
    {
        return $this->chercheurs()->exists();
    }

    /**
     * Obtenir la représentation complète du grade
     */
    public function getFullGradeAttribute()
    {
        return "{$this->sigleGrade} - {$this->nomGrade}";
    }
}


//php artisan make:model Grade -m
