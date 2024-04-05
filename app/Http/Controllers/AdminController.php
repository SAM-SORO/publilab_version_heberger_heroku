<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    public function enregistrerChercheur(){
        return view('lab.admin.enregistrer');
    }

    public function profilAdmin(){
        return view('lab.admin.profil');
    }
}
