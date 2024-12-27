<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AxeRecherche extends Model
{
    use HasFactory;

    protected $table = 'axe_recherches';
    protected $primaryKey = 'idAxeRech';
    protected $fillable = ['titreAxeRech', 'descAxeRech'];

    // Relation avec Laboratoire (many-to-many)
    public function laboratoires()
    {
        return $this->belongsToMany(
            Laboratoire::class, // Modèle lié
            'laboratoire_axe_recherche', // Nom de la table pivot
            'idAxeRech', // Clé étrangère dans la table pivot pour l'AxeRecherche
            'idLabo' // Clé étrangère dans la table pivot pour le Laboratoire
        );

        // Un axe de recherche peut impliquer plusieurs laboratoires,
        // et un laboratoire peut être associé à plusieurs axes de recherche.

    }

    // Relation avec Theme : Un axe de recherche a plusieurs thèmes
    public function themes()
    {
        return $this->hasMany(Theme::class, 'idAxeRech');  // Définition de la relation un-à-plusieurs
    }
}
