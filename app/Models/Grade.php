<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';
    protected $primaryKey = 'idGrade';
    protected $fillable = ['sigleGrade', 'nomGrade'];

    // Relation avec Chercheur (many-to-many)

    public function chercheurs()
    {
        return $this->belongsToMany(Chercheur::class, 'chercheur_grade', 'idGrade', 'idCherch')
                    ->withPivot('dateGrade');
                    
        // Un grade peut être attribué à plusieurs chercheurs, et un chercheur peut avoir plusieurs grades.
        // La table pivot chercheur_grade contient également une colonne dateGrade.
    }

}


//php artisan make:model Grade -m
