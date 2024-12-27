<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revue extends Model
{
    use HasFactory;
    protected $table = 'revues';
    protected $primaryKey = 'idRevue';
    protected $fillable = ['ISSN', 'nomRevue', 'descRevue', 'typeRevue', 'numero', 'volume'];


    // Relation avec les bases d'indexation via la table d'association BDIndexation_Revue
    public function bdIndexations()
    {
        // Retourne les bases d'indexation associées à cette revue
        return $this->belongsToMany(BdIndexation::class, 'bdindexation_revue', 'idRevue', 'idBDInd')
                    ->withPivot(['dateDebut', 'dateFin']);
    }


    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_revue', 'idRevue', 'idArticle')
                    ->withPivot('datePubArt', 'volume', 'numero', 'pageDebut', 'pageFin');

        // Une revue peut contenir plusieurs articles,
        // et un article peut être publié dans plusieurs revues.
        // La table pivot article_revue contient également des informations sur la publication de l'article.
    }

}
