<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $primaryKey = 'idArticle';
    protected $fillable = ['titreArticle', 'resumeArticle','doi'];

    protected $dates = ['datePubArt']; // Cette ligne permet à Eloquent de gérer la conversion en Carbon



    // Relation avec Chercheur (many-to-many)
    public function chercheurs()
    {
        // Retourne les chercheurs ayant co-écrit cet article
        return $this->belongsToMany(Chercheur::class, 'chercheur_article', 'idArticle', 'idCherch');
        // Un article peut être écrit par plusieurs chercheurs,
        // et un chercheur peut écrire plusieurs articles.
    }


    // Relation avec Revue (many-to-many)
    public function revues()
    {
        return $this->belongsToMany(Revue::class, 'article_revue', 'idArticle', 'idRevue')
                    ->withPivot('datePubArt', 'volume', 'numero' ,'pageDebut', 'pageFin');
        // Un article peut être publié dans plusieurs revues,
        // et une revue peut contenir plusieurs articles.
    }



    // Relation avec les chercheurs qui encadrent les doctorants pour cet article (via la table Doctorant_Article_Chercheur)
    public function encadrants()
    {
        return $this->belongsToMany(Chercheur::class, 'doctorant_article_chercheur', 'idArticle', 'idCherch')
                    ->withPivot('idDoc');
    }




    // Relation indirecte avec les doctorants (via la table Doctorant_Article_Chercheur)
    public function doctorants()
    {
        return $this->belongsToMany(Doctorant::class, 'doctorant_article_chercheur', 'idArticle', 'idDoc')
                    ->withPivot('idCherch');
    }




    // // Relation avec les doctorants et chercheurs via la table Doctorant_Article_Chercheur
    // public function doctorantsChercheurs()
    // {
    //     // Retourne les doctorants et chercheurs associés à cet article
    //     return $this->belongsToMany(Doctorant::class, 'doctorant_article_chercheur', 'idArticle', 'idDoc')
    //                 ->withPivot('idCherch');
    // }


}
