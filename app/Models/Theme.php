<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $table = 'themes';
    protected $primaryKey = 'idTheme';
    protected $fillable = ['intituleTheme', 'descTheme', 'idAxeRech'];  // Correctif pour 'fillable'

    // Relation avec AxeRecherche : Un thème appartient à un axe de recherche
    public function axeRecherche()
    {
        return $this->belongsTo(AxeRecherche::class, 'idAxeRech');  // La relation inverse
    }

    /**
     * Relation avec les doctorants (one-to-many).
     * Un thème peut être associé à plusieurs doctorants.
    */
    public function doctorants()
    {
        return $this->hasMany(Doctorant::class, 'idTheme', 'idTheme');
    }

}
