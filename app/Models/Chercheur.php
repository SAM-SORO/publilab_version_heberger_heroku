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

    protected $fillable = [
        'id_labo', 'nom', 'prenom', 'email', 'contact', 'photo', 'password'
    ];

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

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'id_labo');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'id_ch');
    }
}
