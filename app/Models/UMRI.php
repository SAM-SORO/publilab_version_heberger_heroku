<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMRI extends Model
{
    use HasFactory;

    protected $table = 'umris';
    protected $primaryKey = 'idUMRI';

    protected $fillable = [
        'sigleUMRI',
        'nomUMRI',
        'localisationUMRI',
        'idDirecteurUMRI',
        'secretaireUMRI',
        'contactSecretariatUMRI',
        'emailSecretariatUMRI',
        'idEDP'
    ];

    // Relation avec les chercheurs (one-to-many)
    public function chercheurs()
    {
        return $this->hasMany(Chercheur::class, 'idUMRI');
    }

    // Relation avec Laboratoire (one-to-many)
    public function laboratoires()
    {
        return $this->hasMany(Laboratoire::class, 'idUMRI');
    }

    // Relation avec EDP (inverse de one-to-many)
    public function edp()
    {
        return $this->belongsTo(EDP::class, 'idEDP', 'idEDP');
    }

    // Relation avec le directeur (Chercheur)
    public function directeur()
    {
        return $this->belongsTo(Chercheur::class, 'idDirecteurUMRI', 'idCherch');
    }

    // Ajouter la relation avec les doctorants (one-to-many)
    public function doctorants()
    {
        return $this->hasMany(Doctorant::class, 'idUMRI', 'idUMRI');
    }

    // Vérifier si l'UMRI a des dépendances
    public function hasDependencies()
    {
        return $this->laboratoires()->exists() ||
               $this->chercheurs()->exists() ||
               $this->doctorants()->exists();
    }

    // Obtenir le nombre total de chercheurs
    public function getNombreChercheurs()
    {
        return $this->chercheurs()->count();
    }

    // Obtenir le nombre total de laboratoires
    public function getNombreLaboratoires()
    {
        return $this->laboratoires()->count();
    }

    // Ajouter une méthode pour obtenir le nombre de doctorants
    public function getNombreDoctorants()
    {
        return $this->doctorants()->count();
    }

    // Vérifier si l'UMRI a un directeur
    public function hasDirecteur()
    {
        return !is_null($this->idDirecteurUMRI);
    }

    // Obtenir les informations de contact du secrétariat
    public function getContactSecretariatAttribute()
    {
        return [
            'secretaire' => $this->secretaireUMRI,
            'contact' => $this->contactSecretariatUMRI,
            'email' => $this->emailSecretariatUMRI
        ];
    }
}
