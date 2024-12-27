<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    use HasFactory;

    protected $table = 'laboratoires';
    protected $primaryKey = 'idLabo';
    protected $fillable = ['nomLabo', 'anneeCreation', 'localisationLabo', 'adresseLabo', 'telLabo', 'faxLabo', 'emailLabo', 'descLabo', 'idUMRI'];

    // Relation avec Chercheur (one-to-many)
    public function chercheurs()
    {
        return $this->hasMany(Chercheur::class, 'idLabo');
        // Un laboratoire peut avoir plusieurs chercheurs associés.
    }

    // Relation avec UMRI (one-to-one ou belongsTo)
    public function umri()
    {
        return $this->belongsTo(UMRI::class, 'idUMRI', 'idUMRI');
        // Un laboratoire appartient à un UMRI.
    }


    // Relation avec AxeRecherche (many-to-many)
    // Dans le modèle Laboratoire
    public function axesRecherches()
    {
        return $this->belongsToMany(AxeRecherche::class, 'laboratoire_axe_recherche', 'idLabo', 'idAxeRech');
    }
    // Un laboratoire peut être associé à plusieurs axes de recherche,
    // et un axe de recherche peut impliquer plusieurs laboratoires.



}
