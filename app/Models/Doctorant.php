<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctorant extends Model
{
    use HasFactory;

    protected $table = 'doctorants';
    protected $primaryKey = 'idDoc';
    protected $fillable = ['nomDoc', 'prenomDoc', 'idTheme'];

        // Relation avec Theme (inverse de la relation one-to-many)
        public function theme()
        {
            return $this->belongsTo(Theme::class, 'idTheme');
            // Un doctorant est associé à un thème de recherche.
        }

        // Relation avec Chercheur (many-to-many)
        public function encadrants()
        {
            return $this->belongsToMany(Chercheur::class, 'doctorant_chercheur', 'idDoc', 'idCherch')
                ->withPivot('dateDebut', 'dateFin');
            // Un doctorant peut avoir plusieurs chercheurs comme encadrants,
            // et un chercheur peut encadrer plusieurs doctorants.
        }

        // Relation avec les articles et chercheurs via la table Doctorant_Article_Chercheur
        public function articles()
        {
            // Retourne les articles et les chercheurs associés à ce doctorant
            return $this->belongsToMany(Article::class, 'doctorant_article_chercheur', 'idDoc', 'idArticle')
                        ->withPivot('idCher');
        }


        // Relation avec les encadrants (chercheurs) via la relation ternaire avec `Article`
        // public function encadrants()
        // {
        //     return $this->belongsToMany(Chercheur::class, 'doctorant_article_chercheur', 'idDoc', 'idCher');
        // }

        // // Relation avec Article (many-to-many)
        // public function articles()
        // {
        //     return $this->belongsToMany(Article::class, 'doctorant_article_chercheur');
        //     // Un doctorant peut écrire plusieurs articles,
        //     // et un article peut être écrit par plusieurs doctorants.
        // }


}
