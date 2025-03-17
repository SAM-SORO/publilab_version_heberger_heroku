<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Chercheur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'chercheurs';
    protected $primaryKey = 'idCherch';
    protected $guard = "chercheur";

    protected $fillable = [
        'nomCherch',
        'prenomCherch',
        'genreCherch',
        'matriculeCherch',
        'password',
        'emploiCherch',
        'departementCherch',
        'fonctionAdministrativeCherch',
        'specialiteCherch',
        'emailCherch',
        'dateNaissCherch',
        'dateArriveeCherch',
        'telCherch',
        'idUMRI'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'dateNaissCherch' => 'date',
        'dateArriveeCherch' => 'date',
        'genreCherch' => 'string'
    ];

    /**
     * Relation avec les articles (many-to-many)
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'chercheur_article', 'idCherch', 'idArticle')
            ->withTimestamps();
    }

    /**
     * Relation avec les publications (many-to-many)
     */
    public function publications()
    {
        return $this->belongsToMany(Publication::class, 'chercheur_publication', 'idCherch', 'idPub')
            ->withPivot('datePublication')
            ->withTimestamps();
    }

    /**
     * Obtenir les articles par année
     */
    public function getArticlesByYear($year)
    {
        return $this->articles()
            ->whereYear('datePubArt', $year)
            ->orderBy('datePubArt', 'desc')
            ->get();
    }

    /**
     * Obtenir les articles récents
     */
    public function getRecentArticles($limit = 5)
    {
        return $this->articles()
            ->orderBy('datePubArt', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Obtenir les publications par année
     */
    public function getPublicationsByYear($year)
    {
        return $this->publications()
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir les publications récentes
     */
    public function getRecentPublications($limit = 5)
    {
        return $this->publications()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Relation avec les laboratoires (many-to-many)
     */
    public function laboratoires()
    {
        return $this->belongsToMany(Laboratoire::class, 'chercheur_laboratoire', 'idCherch', 'idLabo')
            ->withPivot(['dateAffectation', 'niveau']);
    }

    /**
     * Relation avec Grade (many-to-many)
     */
    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'chercheur_grade', 'idCherch', 'idGrade')
            ->withPivot('dateGrade');
    }

    /**
     * Obtenir le laboratoire principal actuel
     */
    public function getLaboratoirePrincipal()
    {
        return $this->laboratoires()
            ->wherePivot('niveau', 1)
            ->first();
    }


    /**
     * Obtenir le nom complet du chercheur
     */
    public function getFullNameAttribute()
    {
        return "{$this->nomCherch} {$this->prenomCherch}";
    }

    /**
     * Relation avec les doctorants encadrés
     */
    public function doctorantsEncadres()
    {
        return $this->belongsToMany(Doctorant::class, 'doctorant_chercheur', 'idCherch', 'idDoc')
            ->withPivot(['dateDebut', 'dateFin']);
            // ->withTimestamps();
    }

    /**
     * Relation avec les articles et doctorants
     */
    public function doctorantsArticles()
    {
        return $this->belongsToMany(Article::class, 'doctorant_article_chercheur', 'idCherch', 'idArticle')
            ->withPivot('idDoc');
            // ->withTimestamps();
    }

    /**
     * Relation avec les EDPs dirigés
     */
    public function edpsDiriges()
    {
        return $this->hasMany(EDP::class, 'idDirecteurEDP', 'idCherch');
    }

    /**
     * Relation avec les UMRIs dirigés
     */
    public function umrisDiriges()
    {
        return $this->hasMany(UMRI::class, 'idDirecteurUMRI', 'idCherch');
    }

    /**
     * Vérifie si le chercheur est directeur d'un EDP
     */
    public function isDirecteurEDP()
    {
        return $this->edpsDiriges()->exists();
    }

    /**
     * Vérifie si le chercheur est directeur d'une UMRI
     */
    public function isDirecteurUMRI()
    {
        return $this->umrisDiriges()->exists();
    }

    /**
     * Vérifie si le chercheur encadre des doctorants
     */
    public function hasDoctorants()
    {
        return $this->doctorantsEncadres()->exists();
    }

    // Relation avec UMRI (inverse de one-to-many)
    public function umri()
    {
        return $this->belongsTo(UMRI::class, 'idUMRI', 'idUMRI');
    }
}

