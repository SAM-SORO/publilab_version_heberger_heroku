<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDP extends Model
{
    use HasFactory;

    protected $table = 'edps';
    protected $primaryKey = 'idEDP';

    protected $fillable = [
        'sigleEDP',
        'nomEDP',
        'localisationEDP',
        'idDirecteurEDP',
        'secretaireEDP',
        'contactSecretariatEDP',
        'emailSecretariatEDP'
    ];

    // Relation avec UMRI (one-to-many)
    public function umris()
    {
        return $this->hasMany(UMRI::class, 'idEDP');
        // Un EDP peut avoir plusieurs UMRI associés.
    }

    // Relation avec le directeur (Chercheur)
    public function directeur()
    {
        return $this->belongsTo(Chercheur::class, 'idDirecteurEDP', 'idCherch');
    }

    // Vérifier si l'EDP est en relation avec d'autres entités
    public function hasDependencies()
    {
        return $this->umris()->exists();
    }
}
