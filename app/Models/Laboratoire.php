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
     * Relation avec Chercheur (many-to-many)
     */
    public function chercheurs()
    {
        return $this->belongsToMany(Chercheur::class, 'chercheur_laboratoire', 'idLabo', 'idCherch')
            ->withPivot(['niveau', 'dateAffectation']);
            // ->withTimestamps();
    }

    /**
     * Relation avec UMRI (belongsTo)
     */
    public function umri()
    {
        return $this->belongsTo(UMRI::class, 'idUMRI');
    }

    /**
     * Relation avec AxeRecherche (many-to-many)
     */
    public function axesRecherches()
    {
        return $this->belongsToMany(AxeRecherche::class, 'laboratoire_axe_recherche', 'idLabo', 'idAxeRech');
    }

    /**
     * Obtenir les chercheurs par niveau
     */
    public function getChercheursByNiveau($niveau)
    {
        return $this->chercheurs()
            ->wherePivot('niveau', $niveau)
            ->get();
    }

    /**
     * Obtenir les chercheurs affectés après une date donnée
     */
    public function getChercheursByDateAffectation($date)
    {
        return $this->chercheurs()
            ->wherePivot('dateAffectation', '>=', $date)
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
     * Vérifie si un chercheur est membre du laboratoire
     */
    public function hasChercheur($idChercheur)
    {
        return $this->chercheurs()
            ->where('idCherch', $idChercheur)
            ->exists();
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
