<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    // un article est plublier par un seul chercheur
    public function chercheur()
    {
        return $this->belongsTo(Chercheur::class, 'id_ch', 'id_ch');
        /*
        Le deuxième paramètre ('id_ch') spécifie le nom de la colonne dans la table articles qui fait référence à la clé primaire de la table chercheurs
        Le troisième paramètre ('id_ch') spécifie le nom de la colonne dans la table chercheurs qui sert de clé primaire
        */
    }

}
