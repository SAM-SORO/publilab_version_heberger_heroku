<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    use HasFactory;

    protected $table = 'laboratoires';
    protected $primaryKey = 'idLabo';

    protected $fillable = [
        'sigleLabo',
        'nomLabo',
        'anneeCreation',
        'localisationLabo',
        'adresseLabo',
        'telLabo',
        'faxLabo',
        'emailLabo',
        'descLabo',
        'idDirecteurLabo',
        'idUMRI'
    ];

    protected $casts = [
        'anneeCreation' => 'string',
        'sigleLabo' => 'string',
        'nomLabo' => 'string',
        'emailLabo' => 'string'
    ];

    /**
     * Relation avec le directeur du laboratoire
     */
    public function directeur()
    {
        return $this->belongsTo(Chercheur::class, 'idDirecteurLabo', 'idCherch');
    }

    /**
     * Relation avec Chercheur (hasMany)
     */
    public function chercheurs()
    {
        return $this->hasMany(Chercheur::class, 'idLabo', 'idLabo');
    }

    /**
     * Relation avec UMRI (belongsTo)
     */
    public function umri()
    {
        return $this->belongsTo(UMRI::class, 'idUMRI');
    }

    /**
     * Relation avec AxeRecherche (hasMany)
     */
    public function axesRecherches()
    {
        return $this->hasMany(AxeRecherche::class, 'idLabo', 'idLabo');
    }

    /**
     * Obtenir les chercheurs affectés après une date donnée
     */
    public function getChercheursByDateAffectation($date)
    {
        return $this->chercheurs()
            ->where('dateAffectationLabo', '>=', $date)
            ->get();
    }

    /**
     * Obtenir le nom complet du laboratoire (sigle + nom)
     */
    public function getFullNameAttribute()
    {
        return "{$this->sigleLabo} - {$this->nomLabo}";
    }

    /**
     * Vérifie si le laboratoire a des chercheurs
     */
    public function hasChercheurs()
    {
        return $this->chercheurs()->exists();
    }

    /**
     * Vérifie si le laboratoire a des axes de recherche
     */
    public function hasAxesRecherche()
    {
        return $this->axesRecherches()->exists();
    }

    /**
     * Obtenir le nombre total de chercheurs
     */
    public function getNbChercheurs()
    {
        return $this->chercheurs()->count();
    }

    /**
     * Obtenir les informations de contact complètes
     */
    public function getContactInfoAttribute()
    {
        return [
            'adresse' => $this->adresseLabo,
            'telephone' => $this->telLabo,
            'fax' => $this->faxLabo,
            'email' => $this->emailLabo
        ];
    }
}
