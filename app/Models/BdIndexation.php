<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdIndexation extends Model
{
    use HasFactory;

    protected $table = 'bd_indexations';
    protected $primaryKey = 'idBDIndex';
    protected $fillable = ['nomBDInd'];


    // Relation avec les revues via la table d'association BDIndexation_Revue
    public function revues(){
    // Retourne les revues indexées dans cette base de données
    return $this->belongsToMany(Revue::class, 'bdindexation_revue', 'idBDInd', 'idRevue')
                ->withPivot(['dateDebut', 'dateFin']);
    }
}



// php artisan make:model BdIndexation -m
