<?php

namespace App\Http\Controllers;
use Carbon\Carbon; // Importez la classe Carbon pour le formatage de la date
use App\Models\Article;
use App\Models\Chercheur;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VisiteurController extends Controller
{


    public function pageAccueil(){
        return view('lab/visiteur/index');
    }

    public function Articles(){
        // Récupérer les articles paginés avec les informations sur les chercheurs
        $articles = Article::paginate(10);

        $annees = Article::selectRaw('YEAR(created_at) as annee')
                ->distinct()
                ->orderBy('annee', 'desc')
                ->pluck('annee');

        // Retourner la vue avec les articles paginés et les informations sur les chercheurs
        return view('lab.visiteur.articles', compact('articles','annees'));
    }



    public function connexion(){
        return view('lab.auth.login');
    }

    public function inscription(){
        return view('lab.auth.register');
    }


    public function rechercheArticleParAuteur() {
        return view("lab.visiteur.recherche");
    }
}
