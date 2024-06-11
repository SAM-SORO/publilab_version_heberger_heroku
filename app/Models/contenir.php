<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Contenir extends Pivot
{
    protected $table = 'contenir';

    protected $fillable = [
        'num_art', 'num_rev', 'PageDebut', 'PageFin', 'DatePublication', 'Volume', 'Numero'
    ];
}
