<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Chercheur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'chercheurs';
    protected $primaryKey = 'idCherch';
    protected $fillable = ['nomCherch', 'prenomCherch', 'adresse', 'telCherch', 'emailCherch', 'password' ,'specialite', 'idLabo', 'dateArrivee'];

    protected $guard = "chercheur";

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    // Relation avec Article (many-to-many)
    public function articles()
    {
        // return $this->belongsToMany(Article::class, 'chercheur_article');
        return $this->belongsToMany(Article::class, 'chercheur_article', 'idCherch', 'idArticle');
        // Un chercheur peut avoir plusieurs articles,
        // et un article peut être écrit par plusieurs chercheurs.
    }

    // Relation avec Laboratoire (inverse de la relation one-to-many)
    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'idLabo');
        // Un chercheur appartient à un laboratoire.
    }

    // Relation avec Doctorant (many-to-many)
    public function doctorants()
    {
        return $this->belongsToMany(Doctorant::class, 'doctorant_chercheur', 'idCherch', 'idDoc')
                    ->withPivot('dateDebut');
    }
    // Un chercheur peut encadrer plusieurs doctorants,
    // et un doctorant peut avoir plusieurs chercheurs comme encadrants.



    // Relation avec Grade (many-to-many)
    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'chercheur_grade', 'idCherch', 'idGrade')
                    ->withPivot('dateGrade');
        // Un chercheur peut avoir plusieurs grades,
        // et un grade peut être attribué à plusieurs chercheurs.
    }


    // Relation avec les articles et doctorants via la table Doctorant_Article_Chercheur
    public function doctorantsArticles()
    {
        // Retourne les doctorants associés aux articles de ce chercheur
        return $this->belongsToMany(Doctorant::class, 'doctorant_article_chercheur', 'idCherch', 'idDoc')
                    ->withPivot('idArticle');
    }
}
