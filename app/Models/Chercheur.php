<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chercheur extends Model
{
    use HasFactory;
    // un chercheur publiera plusieurs articles
    protected $primaryKey = 'id_ch';

    public function articles(){
        return  $this->hasMany(Article::class,'id_ch','id_ch');
    }

}
