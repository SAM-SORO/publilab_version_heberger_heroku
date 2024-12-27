<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMRI extends Model
{
    use HasFactory;

    protected $table = 'umris';
    protected $primaryKey = 'idUMRI';

    protected $fillable = ['nomUMRI', 'localisationUMI', 'WhatsAppUMRI', 'emailUMRI', 'idEDP'];  // Champs qui peuvent être assignés en masse

    // Relation avec Laboratoire (one-to-many)
    public function laboratoires()
    {
        return $this->hasMany(Laboratoire::class, 'idUMRI');
        // Un UMRI peut avoir plusieurs laboratoires associés.
    }

    // Vérifier si l'UMRI est en relation avec d'autres entités
    public function hasDependencies()
    {
        return $this->laboratoires()->exists();
    }
}
