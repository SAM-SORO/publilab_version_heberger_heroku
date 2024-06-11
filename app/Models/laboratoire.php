<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description', 'adresse'
    ];

    public function chercheurs()
    {
        return $this->hasMany(Chercheur::class, 'id_labo');
    }
}

