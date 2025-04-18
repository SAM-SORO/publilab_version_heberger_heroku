<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AxeRecherche extends Model
{
    use HasFactory;

    protected $table = 'axe_recherches';
    protected $primaryKey = 'idAxeRech';

    protected $fillable = [
        'titreAxeRech',
        'descAxeRech',
        'idLabo'  // Ajout du champ
    ];

    /**
     * Relation avec Laboratoire (one-to-many)
     * Un axe de recherche peut être associé à plusieurs laboratoires,
     * et un laboratoire peut avoir plusieurs axes de recherche
     */
    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'idLabo', 'idLabo');
    }

    /**
     * Relation avec Theme (one-to-many)
     * Un axe de recherche peut avoir plusieurs thèmes
     */
    public function themes()
    {
        return $this->hasMany(Theme::class, 'idAxeRech');
    }

    /**
     * Obtenir les thèmes non attribués de cet axe
     */
    public function getThemesDisponibles()
    {
        return $this->themes()
            ->where('etatAttribution', false)
            ->get();
    }

    /**
     * Obtenir les thèmes attribués de cet axe
     */
    public function getThemesAttribues()
    {
        return $this->themes()
            ->where('etatAttribution', true)
            ->get();
    }

    /**
     * Vérifie si l'axe a des thèmes
     */
    public function hasThemes()
    {
        return $this->themes()->exists();
    }

    /**
     * Obtenir le nombre de thèmes pour cet axe
     */
    public function getThemesCount()
    {
        return $this->themes()->count();
    }
}
