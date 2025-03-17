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
     * Obtenir les chercheurs par ordre alphabétique
     */
    public function getChercheursOrdered()
    {
        return $this->chercheurs()
            ->orderBy('nomCherch')
            ->orderBy('prenomCherch')
            ->get();
    }

    /**
     * Obtenir les doctorants par ordre alphabétique
     */
    public function getDoctorantsOrdered()
    {
        return $this->doctorants()
            ->orderBy('nomDoc')
            ->orderBy('prenomDoc')
            ->get();
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
     *
     * @return string
     */
    public function getFormattedAuthors()
    {
        $auteurs = [];

        // Si l'article a des doctorants
        if ($this->doctorants->isNotEmpty()) {
            // Récupérer les chercheurs associés via doctorant_article_chercheur
            $chercheurIds = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $this->idArticle)
                ->pluck('idCherch')
                ->toArray();

            $chercheurs = Chercheur::whereIn('idCherch', $chercheurIds)
                ->orderBy('nomCherch')
                ->orderBy('prenomCherch')
                ->get();

            // Ajouter les chercheurs à la liste
            foreach ($chercheurs as $chercheur) {
                $auteurs[] = $chercheur->prenomCherch . ' ' . strtoupper($chercheur->nomCherch);
            }

            // Ajouter les doctorants à la liste
            foreach ($this->doctorants as $doctorant) {
                $auteurs[] = $doctorant->prenomDoc . ' ' . strtoupper($doctorant->nomDoc);
            }
        }
        // Si l'article n'a pas de doctorants (chercheurs uniquement)
        else {
            // Récupérer les chercheurs avec leur rang
            $chercheurs = $this->chercheurs()
                ->orderBy('chercheur_article.rang')
                ->get();

            foreach ($chercheurs as $chercheur) {
                $auteurs[] = $chercheur->prenomCherch . ' ' . strtoupper($chercheur->nomCherch);
            }
        }
        
        return implode(', ', $auteurs);
    }
}
