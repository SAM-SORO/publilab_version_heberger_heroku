<?php

namespace App\Http\Controllers;
use Carbon\Carbon; // Importez la classe Carbon pour le formatage de la date
use App\Models\Article;
use App\Models\Revue;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VisiteurController extends Controller
{


    public function pageAccueil()
    {
        // Récupérer les 4 derniers articles publiés
        $articles = Article::with(['revues' => function ($query) {
            $query->orderBy('article_revue.datePubArt', 'desc');
        }])->take(4)->get();

        return view('lab/visiteur/index', compact('articles'));
    }

    //on ne va pas faire la recherche ici en fonction du chercheur connecter
    public function rechercherEtFiltrerArticles(Request $request)
    {
        $query = trim($request->input('query')); // Recherche texte
        $annee = $request->input('annee'); // Année sélectionnée

        // Base de la requête
        $articlesQuery = Article::query();

        // Filtrer par année de publication
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereHas('revues', function ($queryBuilder) use ($annee) {
                $queryBuilder->whereRaw('YEAR(article_revue.datePubArt) = ?', [$annee]);
            });
        }

        // Ajouter la recherche par mot-clé (titre, résumé, revue)
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                // Diviser la requête en mots-clés (chaque mot ou groupe de mots)
                $keywords = explode(' ', $query);

                if (count($keywords) > 1) {
                    // Si plusieurs mots sont saisis, chercher dans les champs pertinents
                    $title = $keywords[0]; // Premier mot comme titre
                    $restQuery = implode(' ', array_slice($keywords, 1)); // Le reste comme autre critère de recherche

                    // Recherche dans le titre de l'article et dans les autres champs
                    $queryBuilder->where('titreArticle', 'like', '%' . $title . '%')
                                ->where(function ($subQuery) use ($restQuery) {
                                    // Recherche supplémentaire dans les autres champs
                                    $subQuery->where('resumeArticle', 'like', '%' . $restQuery . '%')
                                            ->orWhereHas('revues', function ($revueQuery) use ($restQuery) {
                                                $revueQuery->where('nomRevue', 'like', '%' . $restQuery . '%');
                                            })
                                            ->orWhereHas('chercheurs', function ($chercheurQuery) use ($restQuery) {
                                                // Recherche dans le nom et le prénom combinés
                                                $chercheurQuery->whereRaw("CONCAT(nomCherch, ' ', prenomCherch) LIKE ?", ['%' . $restQuery . '%'])
                                                                ->orWhere('prenomCherch', 'like', '%' . $restQuery . '%')
                                                                ->orWhere('nomCherch', 'like', '%' . $restQuery . '%');
                                            });
                                });
                } else {
                    // Si un seul mot est saisi, rechercher dans tous les champs
                    $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                                ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                                ->orWhereHas('revues', function ($revueQuery) use ($query) {
                                    $revueQuery->where('nomRevue', 'like', '%' . $query . '%');
                                })
                                ->orWhereHas('chercheurs', function ($chercheurQuery) use ($query) {
                                    // Recherche dans le nom et prénom
                                    $chercheurQuery->whereRaw("CONCAT(nomCherch, ' ', prenomCherch) LIKE ?", ['%' . $query . '%'])
                                                    ->orWhere('prenomCherch', 'like', '%' . $query . '%')
                                                    ->orWhere('nomCherch', 'like', '%' . $query . '%');
                                });
                }
            });
        }

        // Pagination
        $articles = $articlesQuery->paginate(12);

        // Récupérer les années et les revues
        $annees = DB::table('article_revue')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        $revues = Revue::all();

        return view('lab.visiteur.articles', compact('articles', 'annees', 'revues', 'query', 'annee'));
    }


    public function Articles()
    {
        $articles = Article::with([
                'chercheurs', // Charger les chercheurs associés via la relation 'chercheurs'
            ])
            ->paginate(10);  // Pagination des articles

        // Récupérer les années distinctes des articles
        $annees = Article::selectRaw('YEAR(created_at) as annee')
                        ->distinct()
                        ->orderBy('annee', 'desc')
                        ->pluck('annee');

        return view('lab.visiteur.articles', compact('articles', 'annees'));
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
