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

    /**
     * Les attributs qui sont mass assignable.
     */
    protected $fillable = [
        'titreArticle',
        'lienArticle',
        'doi',
        'resumeArticle',
        'numero',
        'volume',
        'pageDebut',
        'pageFin',
        'datePubArt',
        'idPub',
        'idTypeArticle'
    ];

    /**
     * Les conversions de types pour les attributs.
     */
    protected $casts = [
        'datePubArt' => 'date',
        'numero' => 'integer',
        'volume' => 'integer',
        'pageDebut' => 'integer',
        'pageFin' => 'integer',
        'titreArticle' => 'string',
        'lienArticle' => 'string',
        'doi' => 'string',
        'resumeArticle' => 'string'
    ];

    /**
     * Relation avec Chercheur (many-to-many)
     * Cette relation concerne les chercheurs directement associés à l'article
     */
    public function chercheurs()
    {
        return $this->belongsToMany(Chercheur::class, 'chercheur_article', 'idArticle', 'idCherch')
            ->withPivot('rang')
            ->withTimestamps();
    }

    /**
     * Relation avec Publication (belongsTo)
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'idPub');
    }

    /**
     * Relation avec TypeArticle (belongsTo)
     */
    public function typeArticle()
    {
        return $this->belongsTo(TypeArticle::class, 'idTypeArticle');
    }

    /**
     * Relation avec les doctorants
     * Cette relation concerne les doctorants associés à l'article via la table pivot
     */
    public function doctorants()
    {
        return $this->belongsToMany(
            Doctorant::class,
            'doctorant_article_chercheur',
            'idArticle',
            'idDoc'
        )
        ->withPivot('idCherch');
    }



    /**
     * Obtenir les articles par année
     */
    public static function getArticlesByYear($year)
    {
        return static::whereYear('datePubArt', $year)
            ->orderBy('datePubArt', 'desc')
            ->get();
    }

    /**
     * Obtenir les articles récents
     */
    public static function getRecentArticles($limit = 5)
    {
        return static::orderBy('datePubArt', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Scope pour obtenir tous les articles publiés une année spécifique.
     * Exemple: $articles2025 = Article::ofYear(2025)->get();
     */
    public function scopeOfYear($query, $year)
    {
        return $query->whereYear('datePubArt', $year);
    }

    /**
     * Scope pour les articles d'une publication spécifique
     */
    public function scopeInPublication($query, $idPub)
    {
        return $query->where('idPub', $idPub);
    }

    /**
     * Scope pour les articles d'un type spécifique
     */
    public function scopeOfType($query, $idTypeArticle)
    {
        return $query->where('idTypeArticle', $idTypeArticle);
    }

    /**
     * Obtenir le nombre total d'articles
     */
    public static function getArticlesCount()
    {
        return static::count();
    }

    /**
     * Obtenir tous les auteurs de l'article (chercheurs et doctorants) correctement formatés
     * on souhaite recuperer les auteurs de l'articles
     * soit c'est un article rediger par un chercheur simplement
     *soit c'est un article rediger par un doctorant avec la contribution de different chercheur
     * @return string
     */
    public function getAuthors()
    {
        $auteurs = [];

        // Vérifier si l'article a des chercheurs uniquement (article rédigé par un chercheur)
        if ($this->doctorants->isEmpty()) {
            // Récupérer les chercheurs associés à l'article via la table 'chercheur_article'
            $chercheurs = DB::table('chercheur_article')
                ->where('idArticle', $this->idArticle)
                ->join('chercheurs', 'chercheur_article.idCherch', '=', 'chercheurs.idCherch')
                ->orderBy('chercheur_article.rang') // Ordre par rang
                ->get(['chercheurs.prenomCherch', 'chercheurs.nomCherch']);

            // Ajouter les chercheurs à la liste des auteurs
            foreach ($chercheurs as $chercheur) {
                $auteurs[] = $chercheur->prenomCherch . ' ' . strtoupper($chercheur->nomCherch);
            }
        }
        // Si l'article est rédigé par un doctorant avec des chercheurs
        else {
            // Récupérer les doctorants associés à l'article
            $doctorants = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $this->idArticle)
                ->join('doctorants', 'doctorant_article_chercheur.idDoc', '=', 'doctorants.idDoc')
                ->orderBy('doctorants.nomDoc') // Tri par nom
                ->get(['doctorants.prenomDoc', 'doctorants.nomDoc']);

            // Ajouter les doctorants à la liste des auteurs
            foreach ($doctorants as $doctorant) {
                $auteurs[] = $doctorant->prenomDoc . ' ' . strtoupper($doctorant->nomDoc);
            }

            // Récupérer les chercheurs associés à cet article (par rapport aux doctorants)
            $chercheurs = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $this->idArticle)
                ->join('chercheurs', 'doctorant_article_chercheur.idCherch', '=', 'chercheurs.idCherch')
                ->get(['chercheurs.prenomCherch', 'chercheurs.nomCherch']);

            // Ajouter les chercheurs à la liste des auteurs
            foreach ($chercheurs as $chercheur) {
                $auteurs[] = $chercheur->prenomCherch . ' ' . strtoupper($chercheur->nomCherch);
            }
        }

        // Utiliser array_unique pour éliminer les doublons après avoir formaté les noms
        return implode(', ', array_unique($auteurs));
    }


}
