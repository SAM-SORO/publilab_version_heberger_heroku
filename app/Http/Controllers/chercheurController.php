<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class chercheurController
 extends Controller
{
    //
    protected $primaryKey = 'id_ch';

    public function espaceChercheur(){
        return view('lab.chercheur.index');
    }

    public function profil(){
        return view('lab.chercheur.profil');
    }

    public function publier(){
        return view('lab.chercheur.publier');
    }
}
