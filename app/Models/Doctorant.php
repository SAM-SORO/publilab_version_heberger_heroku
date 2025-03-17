<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Doctorant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'doctorants';
    protected $primaryKey = 'idDoc';

    protected $fillable = [
        'nomDoc',
        'prenomDoc',
        'genreDoc',
        'matriculeDoc',
        'password',
        'emailDoc',
        'telDoc',
        'idTheme'
    ];

    protected $guard = "doctorant";

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    // Relation avec Theme (inverse de la relation one-to-many)
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'idTheme');
        // Un doctorant est associé à un thème de recherche.
    }

    // Relation avec Chercheur (many-to-many) pour les encadrants
    public function encadrants()
    {
        return $this->belongsToMany(Chercheur::class, 'doctorant_chercheur', 'idDoc', 'idCherch')
            ->withPivot(['dateDebut', 'dateFin']);
            
            // ->withTimestamps();

    }

    // Relation avec les articles et chercheurs
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'doctorant_article_chercheur', 'idDoc', 'idArticle')
            ->withPivot('idCherch');
            // ->withTimestamps();
    }

    /**
     * Obtenir le nom complet du doctorant
     */
    public function getFullNameAttribute()
    {
        return "{$this->nomDoc} {$this->prenomDoc}";
    }

    /**
     * Vérifie si le doctorant a un thème attribué
     */
    public function hasTheme()
    {
        return !is_null($this->idTheme);
    }
}
