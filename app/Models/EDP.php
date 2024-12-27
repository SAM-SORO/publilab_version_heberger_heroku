<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDP extends Model
{
    use HasFactory;
    protected $table = 'edps';
    protected $primaryKey = 'idEDP';
    protected $fillable = ['nomEDP', 'localisationEDP', 'PWhatsAppUMI', 'emailUMI'];

    // Relation avec UMRI (one-to-many)
    public function umris()
    {
        return $this->hasMany(UMRI::class, 'idEDP');
        // Un EDP peut avoir plusieurs UMRI associés.
    }

    // Vérifier si l'EDP est en relation avec d'autres entités
    public function hasDependencies()
    {
        return $this->umris()->exists();
    }
}
