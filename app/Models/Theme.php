<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $table = 'themes';
    protected $primaryKey = 'idTheme';

    protected $fillable = [
        'intituleTheme',
        'descTheme',
        'etatAttribution',
        'idAxeRech'
    ];

    protected $casts = [
        'etatAttribution' => 'boolean'
    ];

    // Relation avec AxeRecherche : Un thème appartient à un axe de recherche
    public function axeRecherche()
    {
        return $this->belongsTo(AxeRecherche::class, 'idAxeRech');
    }

    /**
     * Relation avec les doctorants (one-to-many).
     * Un thème peut être associé à plusieurs doctorants.
     */
    public function doctorants()
    {
        return $this->hasMany(Doctorant::class, 'idTheme');
    }

    /**
     * Vérifie si le thème est attribué à au moins un doctorant
     */
    public function isAttributed()
    {
        return $this->doctorants()->exists();
    }
}
