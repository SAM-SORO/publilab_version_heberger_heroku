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
        $articles = Article::paginate(2);

        $annees = Article::selectRaw('YEAR(created_at) as annee')
                ->distinct()
                ->orderBy('annee', 'desc')
                ->pluck('annee');


        // Traitements supplémentaires
        $articles->each(function ($article) {
            // Formater la date de publication en jj/mm/aa
            $article->date_publication = Carbon::parse($article->created_at)->format('d/m/y');



            // Ajouter les informations sur le chercheur à l'article
            $article->chercheur_nom = Chercheur::find($article->id_ch)->nom_ch;
            $article->chercheur_prenom = Chercheur::find($article->id_ch)->prenom_ch;

            // Ajoutez d'autres informations du chercheur si nécessaire
        });

        // Retourner la vue avec les articles paginés et les informations sur les chercheurs
        return view('lab.visiteur.articles', compact('articles','annees'));
    }



    public function connexion(){
        return view('lab.auth.login');
    }

    public function inscription(){
        return view('lab.auth.register');
    }

    public function deconnexion(){
        Auth::logout();
        return redirect()->route('login');
    }


    public function exitEmail(){
        $email = null;
        $user = User::where('email', $email);
        $response = "";
        ($user)? $response = "exist" : $response = "not_exist";

        return response()->json([
            'code' => 200,
            'response'=>$response,
        ]

        );
    }


    public function rechercheArticleParAuteur() {
        return view("lab.visiteur.recherche");
    }
}
