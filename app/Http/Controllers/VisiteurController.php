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
        $articles = Article::with(['publication', 'chercheurs'])
            ->orderBy('datePubArt', 'desc')
            ->take(4)
            ->get();

        return view('lab/visiteur/index', compact('articles'));
    }

    //recher article
    public function rechercherEtFiltrerArticles(Request $request)
    {
        $query = trim($request->input('query')); // Recherche texte
        $annee = $request->input('annee'); // Année sélectionnée

        // Récupérer les années disponibles
        $annees = Article::selectRaw('YEAR(datePubArt) as annee')
            ->distinct()
            ->whereNotNull('datePubArt')
            ->orderBy('annee', 'desc')
            ->pluck('annee');

        // Base de la requête
        $articlesQuery = Article::with(['publication', 'chercheurs', 'doctorants']);

        // Filtrer par année
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereYear('datePubArt', $annee);
        }

        // Recherche par mot-clé
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                // Recherche sur les colonnes de l'article
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                    ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                    ->orWhere('doi', 'like', '%' . $query . '%');
            });

            // Recherche sur les chercheurs (nom + prénom dans les deux sens)
            $articlesQuery->orWhereHas('chercheurs', function ($chercheurQuery) use ($query) {
                $chercheurQuery->whereRaw("LOWER(CONCAT(TRIM(prenomCherch), ' ', TRIM(nomCherch))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                    ->orWhereRaw("LOWER(CONCAT(TRIM(nomCherch), ' ', TRIM(prenomCherch))) LIKE LOWER(?)", ['%' . trim($query) . '%']);
            });

            // Recherche sur les doctorants (nom + prénom dans les deux sens)
            $articlesQuery->orWhereHas('doctorants', function ($doctorantQuery) use ($query) {
                $doctorantQuery->whereRaw("LOWER(CONCAT(TRIM(prenomDoc), ' ', TRIM(nomDoc))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                    ->orWhereRaw("LOWER(CONCAT(TRIM(nomDoc), ' ', TRIM(prenomDoc))) LIKE LOWER(?)", ['%' . trim($query) . '%']);
            });

            // Recherche sur la publication (titre + éditeur)
            $articlesQuery->orWhereHas('publication', function ($pubQuery) use ($query) {
                $pubQuery->where('titrePub', 'like', '%' . $query . '%')
                    ->orWhere('editeurPub', 'like', '%' . $query . '%'); // Ajoute la recherche sur l'éditeur si existant
            });
        }



        $articles = $articlesQuery->orderBy('datePubArt', 'desc')->paginate(10);

        return view('lab.visiteur.articles', compact('articles', 'annees', 'query', 'annee'));
    }


    public function Articles()
    {
        // Base de la requête
        $articles = Article::with(['publication', 'chercheurs', 'doctorants'])
            ->orderBy('datePubArt', 'desc')
            ->paginate(10);

        // Récupérer les années distinctes des articles
        $annees = Article::selectRaw('YEAR(datePubArt) as annee')
            ->distinct()
            ->whereNotNull('datePubArt')
            ->orderBy('annee', 'desc')
            ->pluck('annee');

        // Initialiser les variables de recherche et filtre à null
        $query = null;
        $annee = null;

        return view('lab.visiteur.articles', compact('articles', 'annees', 'query', 'annee'));
    }




    public function connexion(){
        return view('lab.auth.login');
    }

    public function inscription(){
        return view('lab.auth.register');
    }


    public function enregistrerArticle(Request $request)
    {
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:200',
            'lienArticle' => 'nullable|string|url',
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:100',
            'chercheurs' => 'required|array|min:1',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
            'publication' => 'nullable|exists:publications,idPub',
            'datePubArt' => 'required|date',
            'volume' => 'nullable|integer',
            'numero' => 'nullable|integer',
            'pageDebut' => 'nullable|integer|min:1',
            'pageFin' => 'nullable|integer|gte:pageDebut'
        ]);

        DB::beginTransaction(); // Garder la transaction car on a une relation many-to-many

        try {
            $article = new Article();
            $article->titreArticle = $validatedData['titreArticle'];
            $article->lienArticle = $validatedData['lienArticle'] ?? null;
            $article->resumeArticle = $validatedData['resumeArticle'] ?? null;
            $article->doi = $validatedData['doi'] ?? null;
            $article->datePubArt = $validatedData['datePubArt'];
            $article->numero = $validatedData['numero'] ?? null;
            $article->volume = $validatedData['volume'] ?? null;
            $article->pageDebut = $validatedData['pageDebut'] ?? null;
            $article->pageFin = $validatedData['pageFin'] ?? null;
            $article->idPub = $validatedData['publication'] ?? null;
            $article->save();

            // Attacher les chercheurs avec leur rang
            $chercheurData = [];
            foreach ($validatedData['chercheurs'] as $index => $chercheurId) {
                $chercheurData[$chercheurId] = ['rang' => $index + 1];
            }
            $article->chercheurs()->attach($chercheurData);

            DB::commit();
            return redirect()->route('chercheur.espace')
                ->with('success', 'Article enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function rechercheArticleParAuteur()
    {
        try {
            return view("lab.visiteur.recherche");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
