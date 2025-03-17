<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Chercheur;
use App\Models\Contenir;
use App\Models\Document;
use App\Models\Publication;
use App\Models\BdIndexation;
use App\Models\TypeArticle;
use App\Models\Doctorant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;


class chercheurController extends Controller
{


    public function index()
    {
        // Récupérer le chercheur connecté
        $chercheurConnecte = Auth::user();

        // Vérifier si un chercheur est connecté
        if (!$chercheurConnecte) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Compter les articles où le chercheur est directement lié via chercheur_article
        $articlesDirects = Article::whereHas('chercheurs', function ($query) use ($chercheurConnecte) {
            $query->where('chercheurs.idCherch', $chercheurConnecte->idCherch);
        })->count();

        // Compter les articles où le chercheur est mentionné dans doctorant_article_chercheur
        $articlesIndirects = Article::whereExists(function ($query) use ($chercheurConnecte) {
            $query->select(DB::raw(1))
                  ->from('doctorant_article_chercheur')
                  ->whereRaw('doctorant_article_chercheur.idArticle = articles.idArticle')
                  ->where('doctorant_article_chercheur.idCherch', $chercheurConnecte->idCherch);
        })->count();

        // Compter le nombre total d'articles uniques
        $NbreArticles = Article::where(function($query) use ($chercheurConnecte) {
            // Articles où le chercheur est directement lié via chercheur_article
            $query->whereHas('chercheurs', function($q) use ($chercheurConnecte) {
                $q->where('chercheurs.idCherch', $chercheurConnecte->idCherch);
            });

            // OU articles où le chercheur est mentionné dans doctorant_article_chercheur
            $query->orWhereExists(function($q) use ($chercheurConnecte) {
                $q->select(DB::raw(1))
                  ->from('doctorant_article_chercheur')
                  ->whereRaw('doctorant_article_chercheur.idArticle = articles.idArticle')
                  ->where('doctorant_article_chercheur.idCherch', $chercheurConnecte->idCherch);
            });
        })->count();

        // Retourner la vue avec les données
        return view('lab.chercheur.index', compact('NbreArticles'));
    }

    public function listeArticles(Request $request)
    {
        try {
            $chercheur = Auth::user();

            // Initialiser la requête de base pour inclure les deux types de relations
            $articlesQuery = Article::with(['publication', 'typeArticle', 'chercheurs', 'doctorants'])
                ->where(function($query) use ($chercheur) {
                    // Articles où le chercheur est directement lié via chercheur_article
                    $query->whereHas('chercheurs', function($q) use ($chercheur) {
                        $q->where('chercheurs.idCherch', $chercheur->idCherch);
                    });

                    // OU articles où le chercheur est mentionné dans doctorant_article_chercheur
                    $query->orWhereExists(function($q) use ($chercheur) {
                        $q->select(DB::raw(1))
                          ->from('doctorant_article_chercheur')
                          ->whereRaw('doctorant_article_chercheur.idArticle = articles.idArticle')
                          ->where('doctorant_article_chercheur.idCherch', $chercheur->idCherch);
                    });
                });

            // Récupérer les paramètres de filtre
            $query = $request->input('query');
            $annee = $request->input('annee');
            $typeArticleId = $request->input('typeArticle');
            $typeAuteur = $request->input('typeAuteur');

            // Filtre par mot-clé
            if ($query) {
                $articlesQuery->where(function ($queryBuilder) use ($query) {
                    // Recherche dans les colonnes de l'article
                    $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                        ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                        ->orWhere('doi', 'like', '%' . $query . '%');

                    // Recherche dans la publication (titre et éditeur)
                    $queryBuilder->orWhereHas('publication', function ($pubQuery) use ($query) {
                        $pubQuery->where('titrePub', 'like', '%' . $query . '%')
                            ->orWhere('editeurPub', 'like', '%' . $query . '%');
                    });

                    // Recherche par chercheur (nom + prénom)
                    $queryBuilder->orWhereHas('chercheurs', function ($chercheurQuery) use ($query) {
                        $chercheurQuery->whereRaw("LOWER(CONCAT(TRIM(prenomCherch), ' ', TRIM(nomCherch))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                            ->orWhereRaw("LOWER(CONCAT(TRIM(nomCherch), ' ', TRIM(prenomCherch))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                            ->orWhere('prenomCherch', 'like', '%' . $query . '%')
                            ->orWhere('nomCherch', 'like', '%' . $query . '%');
                    });

                    // Recherche par doctorant (nom + prénom)
                    $queryBuilder->orWhereHas('doctorants', function ($doctorantQuery) use ($query) {
                        $doctorantQuery->whereRaw("LOWER(CONCAT(TRIM(prenomDoc), ' ', TRIM(nomDoc))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                            ->orWhereRaw("LOWER(CONCAT(TRIM(nomDoc), ' ', TRIM(prenomDoc))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                            ->orWhere('prenomDoc', 'like', '%' . $query . '%')
                            ->orWhere('nomDoc', 'like', '%' . $query . '%');
                    });
                });
            }

            // Filtre par année
            if ($annee && $annee != 'Tous') {
                $articlesQuery->whereYear('datePubArt', $annee);
            }

            // Filtre par type d'article
            if ($typeArticleId && $typeArticleId != 'Tous') {
                $articlesQuery->where('idTypeArticle', $typeArticleId);
            }

            // Filtre par type d'auteur
            if ($typeAuteur && $typeAuteur != 'Tous') {
                $articlesQuery->where('typeAuteur', $typeAuteur);
            }

            // Récupérer les articles filtrés et paginés
            $articles = $articlesQuery->orderBy('created_at', 'desc')->paginate(12);

            // Conserver les paramètres de filtre dans la pagination
            $articles->appends([
                'query' => $query,
                'annee' => $annee,
                'typeArticle' => $typeArticleId,
                'typeAuteur' => $typeAuteur
            ]);

            // Récupérer les années distinctes pour le filtre
            $annees = DB::table('articles')
                ->selectRaw('YEAR(datePubArt) as year')
                ->distinct()
                ->whereNotNull('datePubArt')
                ->orderBy('year', 'desc')
                ->pluck('year');

            // Récupérer les données pour les autres filtres
            $publications = Publication::orderBy('titrePub', 'asc')->get();
            $chercheurs = Chercheur::orderBy('nomCherch', 'asc')
                ->orderBy('prenomCherch', 'asc')
                ->get();
            $doctorants = Doctorant::orderBy('nomDoc', 'asc')
                ->orderBy('prenomDoc', 'asc')
                ->get();
            $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();

            return view('lab.chercheur.liste_article', compact(
                'articles',
                'annees',
                'publications',
                'typeArticles',
                'chercheurs',
                'doctorants',
                'query',
                'annee',
                'typeArticleId',
                'typeAuteur'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }




    public function enregistrerArticle(Request $request)
    {
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:200',
            'lienArticle' => 'nullable|string|url',
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:100',
            'chercheurs' => 'nullable|array',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
            'doctorants' => 'nullable|array',
            'doctorants.*' => 'exists:doctorants,idDoc',
            'idPub' => 'nullable|exists:publications,idPub',
            'datePubArt' => 'nullable|date',
            'volume' => 'nullable|integer',
            'numero' => 'nullable|integer',
            'pageDebut' => 'nullable|integer|min:1',
            'pageFin' => 'nullable|integer|gte:pageDebut',
            'idTypeArticle' => 'nullable|exists:type_articles,idTypeArticle'
        ]);

        DB::beginTransaction();

        try {
            // Créer un nouvel article
            $article = new Article();
            $article->titreArticle = $validatedData['titreArticle'];
            $article->lienArticle = $validatedData['lienArticle'] ?? null;
            $article->resumeArticle = $validatedData['resumeArticle'] ?? null;
            $article->doi = $validatedData['doi'] ?? null;
            $article->datePubArt = $validatedData['datePubArt'] ?? null;
            $article->numero = $validatedData['numero'] ?? null;
            $article->volume = $validatedData['volume'] ?? null;
            $article->pageDebut = $validatedData['pageDebut'] ?? null;
            $article->pageFin = $validatedData['pageFin'] ?? null;
            $article->idPub = $validatedData['idPub'] ?? null;
            $article->idTypeArticle = $validatedData['idTypeArticle'] ?? null;
            $article->save();

            // Récupérer le chercheur connecté
            $chercheurConnecte = Auth::user();

            // S'assurer que le chercheur connecté est inclus dans la liste des chercheurs
            if (empty($validatedData['chercheurs'])) {
                $validatedData['chercheurs'] = [$chercheurConnecte->idCherch];
            } elseif (!in_array($chercheurConnecte->idCherch, $validatedData['chercheurs'])) {
                $validatedData['chercheurs'][] = $chercheurConnecte->idCherch;
            }

            // Si des doctorants sont sélectionnés
            if (!empty($validatedData['doctorants'])) {
                foreach ($validatedData['doctorants'] as $doctorantId) {
                    // Pour chaque chercheur, créer une entrée dans doctorant_article_chercheur
                    foreach ($validatedData['chercheurs'] as $chercheurId) {
                        DB::table('doctorant_article_chercheur')->insert([
                            'idArticle' => $article->idArticle,
                            'idDoc' => $doctorantId,
                            'idCherch' => $chercheurId
                        ]);
                    }
                }
            } else {
                // Si aucun doctorant n'est sélectionné, associer les chercheurs à l'article avec leur rang
                $chercheurData = [];
                foreach ($validatedData['chercheurs'] as $index => $chercheurId) {
                    $chercheurData[$chercheurId] = ['rang' => $index + 1];
                }

                // Attacher les chercheurs à l'article
                $article->chercheurs()->attach($chercheurData);
            }

            DB::commit();
            return redirect()->route('chercheur.listeArticles')->with('success', 'Article enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', "Une erreur est survenue : " . $e->getMessage());
        }
    }



    public function modifierArticle($id)
    {
        try {
            $article = Article::with(['chercheurs', 'doctorants', 'publication', 'typeArticle'])->findOrFail($id);

            // Vérifier que l'article appartient bien au chercheur connecté
            $chercheurConnecte = Auth::user();
            $articleAppartientAuChercheur = $article->chercheurs->contains('idCherch', $chercheurConnecte->idCherch) ||
                                            DB::table('doctorant_article_chercheur')
                                                ->where('idArticle', $article->idArticle)
                                                ->where('idCherch', $chercheurConnecte->idCherch)
                                                ->exists();

            if (!$articleAppartientAuChercheur) {
                return redirect()->route('chercheur.listeArticles')
                    ->with('error', 'Vous n\'êtes pas autorisé à modifier cet article.');
            }

            // Récupérer les données pour les listes déroulantes, triées par ordre alphabétique
            $publications = Publication::orderBy('titrePub', 'asc')->get();
            $chercheurs = Chercheur::orderBy('nomCherch', 'asc')->orderBy('prenomCherch', 'asc')->get();
            $doctorants = Doctorant::orderBy('nomDoc', 'asc')->orderBy('prenomDoc', 'asc')->get();
            $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();

            // Récupérer les IDs des chercheurs associés à cet article
            $chercheurIds = [];

            // 1. Récupérer les chercheurs directement associés (via chercheur_article)
            $chercheurIds = $article->chercheurs->pluck('idCherch')->toArray();

            // 2. Si l'article a des doctorants, récupérer aussi les chercheurs associés via doctorant_article_chercheur
            if ($article->doctorants->isNotEmpty()) {
                // Récupérer les chercheurs associés via la table doctorant_article_chercheur
                $chercheurIdsFromDoctorants = DB::table('doctorant_article_chercheur')
                    ->where('idArticle', $article->idArticle)
                    ->pluck('idCherch')
                    ->toArray();

                // Fusionner les deux listes et éliminer les doublons
                $chercheurIds = array_unique(array_merge($chercheurIds, $chercheurIdsFromDoctorants));
            }

            // Récupérer les IDs des doctorants associés à cet article
            $doctorantIds = $article->doctorants->pluck('idDoc')->toArray();

            return view('lab.chercheur.modifier_article', compact(
                'article',
                'publications',
                'chercheurs',
                'doctorants',
                'typeArticles',
                'chercheurIds',
                'doctorantIds'
            ));
        } catch (\Exception $e) {
            return redirect()->route('chercheur.listeArticles')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }



    public function updateArticle(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:200',
            'lienArticle' => 'nullable|string|url',
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:100',
            'chercheurs' => 'nullable|array',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
            'doctorants' => 'nullable|array',
            'doctorants.*' => 'exists:doctorants,idDoc',
            'idPub' => 'nullable|exists:publications,idPub',
            'datePubArt' => 'nullable|date',
            'volume' => 'nullable|integer',
            'numero' => 'nullable|integer',
            'pageDebut' => 'nullable|integer|min:1',
            'pageFin' => 'nullable|integer|gte:pageDebut',
            'idTypeArticle' => 'nullable|exists:type_articles,idTypeArticle'
        ]);

        DB::beginTransaction();

        try {
            // Récupérer l'article
            $article = Article::findOrFail($id);

            // Vérifier que l'article appartient bien au chercheur connecté
            $chercheurConnecte = Auth::user();
            $articleAppartientAuChercheur = $article->chercheurs->contains('idCherch', $chercheurConnecte->idCherch) ||
                                            DB::table('doctorant_article_chercheur')
                                                ->where('idArticle', $article->idArticle)
                                                ->where('idCherch', $chercheurConnecte->idCherch)
                                                ->exists();

            if (!$articleAppartientAuChercheur) {
                return redirect()->route('chercheur.listeArticles')
                    ->with('error', 'Vous n\'êtes pas autorisé à modifier cet article.');
            }

            // Mettre à jour les informations de base de l'article
            $article->update([
                'titreArticle' => $validatedData['titreArticle'],
                'lienArticle' => $validatedData['lienArticle'] ?? null,
                'resumeArticle' => $validatedData['resumeArticle'] ?? null,
                'doi' => $validatedData['doi'] ?? null,
                'datePubArt' => $validatedData['datePubArt'] ?? null,
                'numero' => $validatedData['numero'] ?? null,
                'volume' => $validatedData['volume'] ?? null,
                'pageDebut' => $validatedData['pageDebut'] ?? null,
                'pageFin' => $validatedData['pageFin'] ?? null,
                'idPub' => $validatedData['idPub'] ?? null,
                'idTypeArticle' => $validatedData['idTypeArticle'] ?? null,
            ]);

            // Supprimer toutes les anciennes associations
            DB::table('doctorant_article_chercheur')->where('idArticle', $article->idArticle)->delete();
            $article->chercheurs()->detach(); // Détacher tous les chercheurs
            $article->doctorants()->detach(); // Détacher tous les doctorants

            // S'assurer que le chercheur connecté est inclus dans la liste des chercheurs
            if (!in_array($chercheurConnecte->idCherch, $validatedData['chercheurs'])) {
                $validatedData['chercheurs'][] = $chercheurConnecte->idCherch;
            }

            // Si des doctorants sont sélectionnés, associer les chercheurs à ces doctorants
            if (!empty($validatedData['doctorants'])) {
                $article->save();

                foreach ($validatedData['doctorants'] as $doctorantId) {
                    // Pour chaque chercheur, on les associe avec le doctorant
                    foreach ($validatedData['chercheurs'] as $chercheurId) {
                        DB::table('doctorant_article_chercheur')->insert([
                            'idArticle' => $article->idArticle,
                            'idDoc' => $doctorantId,
                            'idCherch' => $chercheurId,
                        ]);
                    }
                }
            } else {
                // Si aucun doctorant n'est sélectionné, associer les chercheurs à l'article avec leur rang
                $chercheurData = [];
                foreach ($validatedData['chercheurs'] as $index => $chercheurId) {
                    $chercheurData[$chercheurId] = ['rang' => $index + 1];
                }

                // Attacher les chercheurs à l'article
                $article->chercheurs()->attach($chercheurData);

                $article->save();
            }

            DB::commit();
            return redirect()->route('chercheur.listeArticles')->with('success', 'Article modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', "Une erreur est survenue : " . $e->getMessage());
        }
    }




    public function rechercheArticle(Request $request)
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer une recherche.');
        }

        $chercheurConnecte = Auth::user();
        $query = $request->input('query');
        $annee = $request->input('annee');

        // Base de la requête : récupérer tous les articles du chercheur connecté
        $articlesQuery = Article::with(['publication', 'typeArticle', 'chercheurs', 'doctorants'])
            ->where(function($query) use ($chercheurConnecte) {
                // Articles où le chercheur est directement lié via chercheur_article
                $query->whereHas('chercheurs', function($q) use ($chercheurConnecte) {
                    $q->where('chercheurs.idCherch', $chercheurConnecte->idCherch);
                });

                // OU articles où le chercheur est mentionné dans doctorant_article_chercheur
                $query->orWhereExists(function($q) use ($chercheurConnecte) {
                    $q->select(DB::raw(1))
                      ->from('doctorant_article_chercheur')
                      ->whereRaw('doctorant_article_chercheur.idArticle = articles.idArticle')
                      ->where('doctorant_article_chercheur.idCherch', $chercheurConnecte->idCherch);
                });
            });

        // Ajouter les conditions de recherche selon le terme
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                             ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                             ->orWhere('doi', 'like', '%' . $query . '%');

                // Recherche dans la publication (titre et éditeur)
                $queryBuilder->orWhereHas('publication', function ($pubQuery) use ($query) {
                    $pubQuery->where('titrePub', 'like', '%' . $query . '%')
                        ->orWhere('editeurPub', 'like', '%' . $query . '%');
                });

                // Recherche par chercheur (nom + prénom)
                $queryBuilder->orWhereHas('chercheurs', function ($chercheurQuery) use ($query) {
                    $chercheurQuery->whereRaw("LOWER(CONCAT(TRIM(prenomCherch), ' ', TRIM(nomCherch))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                        ->orWhereRaw("LOWER(CONCAT(TRIM(nomCherch), ' ', TRIM(prenomCherch))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                        ->orWhere('prenomCherch', 'like', '%' . $query . '%')
                        ->orWhere('nomCherch', 'like', '%' . $query . '%');
                });

                // Recherche par doctorant (nom + prénom)
                $queryBuilder->orWhereHas('doctorants', function ($doctorantQuery) use ($query) {
                    $doctorantQuery->whereRaw("LOWER(CONCAT(TRIM(prenomDoc), ' ', TRIM(nomDoc))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                        ->orWhereRaw("LOWER(CONCAT(TRIM(nomDoc), ' ', TRIM(prenomDoc))) LIKE LOWER(?)", ['%' . trim($query) . '%'])
                        ->orWhere('prenomDoc', 'like', '%' . $query . '%')
                        ->orWhere('nomDoc', 'like', '%' . $query . '%');
                });
            });
        }

        // Filtrer par année de publication
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereYear('datePubArt', $annee);
        }

        // Pagination des résultats
        $articles = $articlesQuery->orderBy('created_at', 'desc')->paginate(12);

        // Conserver les paramètres de filtre dans la pagination
        $articles->appends([
            'query' => $query,
            'annee' => $annee
        ]);

        // Récupérer les années de publication distinctes
        $annees = DB::table('articles')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->whereNotNull('datePubArt')
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        // Récupérer toutes les données nécessaires pour les filtres et l'affichage
        $publications = Publication::orderBy('titrePub', 'asc')->get();
        $chercheurs = Chercheur::orderBy('nomCherch', 'asc')
                    ->orderBy('prenomCherch', 'asc')
                    ->get();
        $doctorants = Doctorant::orderBy('nomDoc', 'asc')
                    ->orderBy('prenomDoc', 'asc')
                    ->get();
        $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();

        // Variables pour les filtres actifs
        $typeArticleId = null;
        $typeAuteur = null;

        // Retourner la vue avec toutes les données nécessaires
        return view('lab.chercheur.liste_article', compact(
            'articles',
            'annees',
            'publications',
            'typeArticles',
            'chercheurs',
            'doctorants',
            'query',
            'annee',
            'typeArticleId',
            'typeAuteur'
        ));
    }


    public function filtreArticle(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        $chercheurConnecte = Auth::user();

        if (!$chercheurConnecte) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $annee = $request->input('annee'); // Année sélectionnée dans le filtre
        $query = $request->input('query'); // Recherche texte

        // Base de la requête : récupérer tous les articles du chercheur connecté
        $articlesQuery = Article::whereHas('chercheurs', function ($queryBuilder) use ($chercheurConnecte) {
            $queryBuilder->where('chercheurs.idCherch', $chercheurConnecte->idCherch);
        });

        // Filtrer par année de publication dans la table pivot 'article_revue'
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereHas('publication', function ($queryBuilder) use ($annee) {
                $queryBuilder->whereRaw('YEAR(articles.datePubArt) = ?', [$annee]);
            });
        }

        // Ajouter les conditions de recherche selon le terme
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                            ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                            ->orWhereHas('publication', function ($pubQuery) use ($query) {
                                $pubQuery->where('titrePub', 'like', '%' . $query . '%');
                            });
            });
        }

        // Pagination des résultats
        $articles = $articlesQuery->paginate(12);

        // Récupérer les années de publication distinctes depuis la table pivot 'article_revue'
        $annees = DB::table('articles')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        // Récupérer toutes les publications pour le filtre
        $publications = Publication::all();
        $chercheurs = Chercheur::all();


        // Retourner la vue avec toutes les données nécessaires
        return view('lab.chercheur.liste_article', compact('articles', 'annees', 'publications', 'query', 'annee', 'chercheurs'));
    }


    public function supprimerArticle($id)
    {
        try {
            // Récupérer l'article
            $article = Article::findOrFail($id);

            DB::beginTransaction();

            // Supprimer les relations dans la table pivot doctorant_article_chercheur
            DB::table('doctorant_article_chercheur')
                ->where('idArticle', $article->idArticle)
                ->delete();

            // Détacher les chercheurs et doctorants
            $article->chercheurs()->detach();
            $article->doctorants()->detach();

            // Supprimer l'article
            $article->delete();

            DB::commit();

            return redirect()->route('chercheur.listeArticles')
                ->with('success', 'Article supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'article : ' . $e->getMessage());
        }
    }



    public function modifierProfil(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nomCherch' => 'required|string|max:255',
            'prenomCherch' => 'required|string|max:255',
            'genreCherch' => 'nullable|in:M,F',
            'matriculeCherch' => 'required|string|max:20',
            'emploiCherch' => 'nullable|string|max:100',
            'departementCherch' => 'nullable|string|max:100',
            'fonctionAdministrativeCherch' => 'nullable|string|max:100',
            'specialiteCherch' => 'nullable|string|max:100',
            'emailCherch' => 'required|email|max:100|unique:chercheurs,emailCherch,'.auth()->user()->idCherch.',idCherch',
            'telCherch' => 'nullable|string|max:15',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $chercheur = auth()->user();

            // Mise à jour des informations de base
            $updateData = collect($validated)->except(['current_password', 'new_password'])->toArray();

            // Gestion du mot de passe
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $chercheur->password)) {
                    return redirect()->route('chercheur.profil')
                        ->withInput()
                        ->with('error', 'Le mot de passe actuel est incorrect.');
                }

                if ($request->filled('new_password')) {
                    $updateData['password'] = Hash::make($request->new_password);
                }
            }

            // Utilisation de la méthode update pour éviter l'erreur
            $chercheur->update($updateData);

            DB::commit();
            return redirect()->route('chercheur.profil')
                ->with('success', 'Profil mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('chercheur.profil')
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du profil : ' . $e->getMessage());
        }
    }




    public function profil(){
        $chercheurConnecter = Auth::user();
        $chercheur = Chercheur::findOrFail($chercheurConnecter->idCherch);
        return view('lab.chercheur.profil' , compact('chercheur'));
    }


    public function listeArticle()
    {
        try {
            // Récupérer le chercheur connecté
            $chercheur = Auth::user();

            // Récupérer les articles du chercheur avec leurs relations
            $articles = Article::with(['publication', 'typeArticle'])
                ->whereHas('chercheurs', function($query) use ($chercheur) {
                    $query->where('idCherch', $chercheur->idCherch);
                })
                ->orderBy('datePubArt', 'desc')
                ->get();

            // Récupérer les publications correspondantes avec leurs articles
            $publications = Publication::whereHas('articles', function($query) use ($chercheur) {
                $query->whereHas('chercheurs', function($q) use ($chercheur) {
                    $q->where('idCherch', $chercheur->idCherch);
                });
            })
            ->with(['articles' => function($query) {
                $query->orderBy('datePubArt', 'desc');
            }])
            ->get();

            // Récupérer les types d'articles pour le filtre
            $typeArticles = TypeArticle::all();

            return view('lab.chercheur.liste_article', compact('articles', 'publications', 'typeArticles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}

