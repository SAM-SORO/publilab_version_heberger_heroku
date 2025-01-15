<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Chercheur;
use App\Models\Contenir;
use App\Models\Document;
use App\Models\Revue;
use App\Models\BdIndexation;
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
        // Récupérer le chercheur connectép
        $chercheurConnecte = Auth::user();

        // Vérifier si un chercheur est connecté
        if (!$chercheurConnecte) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Compter le nombre d'articles associés au chercheur connecté
        // $NbreArticles = $chercheurConnecte->articles()->count();

        $NbreArticles = Article::whereHas('chercheurs', function ($query) use ($chercheurConnecte) {
            $query->where('chercheur_article.idCherch', $chercheurConnecte->idCherch);
        })->count();


        // Retourner la vue avec les données
        return view('lab.chercheur.index', compact('NbreArticles'));
    }


    public function listeArticles()
    {
        // Récupérer le chercheur connecté
        $chercheurConnecte = Auth::user(); // Récupère le chercheur connecté

        // Récupérer les articles liés au chercheur connecté, ordonnés par date de publication (du plus récent au plus ancien)
        $articles = Article::whereHas('chercheurs', function ($query) use ($chercheurConnecte) {
            $query->where('chercheur_article.idCherch', $chercheurConnecte->idCherch); // Spécifier la table 'chercheur_article'
        })
        ->with(['revues' => function ($query) {
            $query->orderBy('article_revue.datePubArt', 'desc'); // Ordre décroissant
        }])
        ->orderByDesc('created_at') // Si la colonne 'created_at' correspond à la date d'ajout des articles
        ->paginate(12);

        // Récupérer les années distinctes des dates de publication
        $annees = DB::table('article_revue')
            ->selectRaw('YEAR(datePubArt) as year')
            ->distinct()
            ->orderBy('year', 'desc') // Ordre décroissant
            ->pluck('year');

        // Récupérer toutes les revues et chercheurs
        $revues = Revue::all();
        $chercheurs = Chercheur::all();

        // Retourner la vue avec les données
        return view('lab.chercheur.liste_article', compact('articles', 'annees', 'revues', 'chercheurs'));
    }




    //fait pour l'autre profil
    public function enregistrerArticle(Request $request)
    {
        $chercheurConnecte = Auth::user(); // Récupère le chercheur connecté

        // Validation des champs avec messages personnalisés
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:255', // Champ obligatoire
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:255',
            'chercheurs' => 'nullable|array',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
            'revue' => 'nullable|exists:revues,idRevue', // Revue est nullable
            'datePubArt' => 'nullable|date', // Nullable dans la table pivot
            'volume' => 'nullable|integer',
            'numero' => 'nullable|integer',
            'pageDebut' => 'nullable|integer|min:1',
            'pageFin' => 'nullable|integer|gte:pageDebut', // Si pageFin est fourni, il doit être >= pageDebut
        ], [
            'titreArticle.required' => 'Le titre de l\'article est obligatoire.',
            'titreArticle.string' => 'Le titre de l\'article doit être une chaîne de caractères.',
            'titreArticle.max' => 'Le titre de l\'article ne doit pas dépasser 255 caractères.',
            'resumeArticle.string' => 'Le résumé doit être une chaîne de caractères.',
            'doi.string' => 'Le DOI doit être une chaîne de caractères.',
            'doi.max' => 'Le DOI ne doit pas dépasser 255 caractères.',
            'chercheurs.array' => 'La liste des chercheurs doit être un tableau.',
            'chercheurs.*.exists' => 'Un des chercheurs sélectionnés n\'existe pas dans la base de données.',
            'revue.exists' => 'La revue sélectionnée n\'existe pas dans la base de données.',
            'datePubArt.date' => 'La date de publication doit être une date valide.',
            'volume.integer' => 'Le volume doit être un entier.',
            'numero.integer' => 'Le numéro doit être un entier.',
            'pageDebut.integer' => 'La page de début doit être un entier.',
            'pageDebut.min' => 'La page de début doit être au moins égale à 1.',
            'pageFin.integer' => 'La page de fin doit être un entier.',
            'pageFin.gte' => 'La page de fin doit être supérieure ou égale à la page de début.',
        ]);

        DB::beginTransaction();

        try {
            // Créer un nouvel article
            $article = new Article();
            $article->titreArticle = $validatedData['titreArticle'];
            $article->resumeArticle = $validatedData['resumeArticle'] ?? null;
            $article->doi = $validatedData['doi'] ?? null;
            $article->save();

            DB::transaction(function () use ($article, $validatedData, $chercheurConnecte) {
                // Associer les chercheurs
                if (empty($validatedData['chercheurs'])) {
                    $article->chercheurs()->attach($chercheurConnecte->idCherch);
                } else {
                    $article->chercheurs()->attach($validatedData['chercheurs']);
                }

                // Associer la revue
                if (!empty($validatedData['revue'])) {
                    $article->revues()->attach($validatedData['revue'], [
                        'datePubArt' => $validatedData['datePubArt'] ?? null,
                        'volume' => $validatedData['volume'] ?? null,
                        'numero' => $validatedData['numero'] ?? null,
                        'pageDebut' => $validatedData['pageDebut'] ?? null,
                        'pageFin' => $validatedData['pageFin'] ?? null,
                    ]);
                }
            });
            // Si tout s'est bien passé, valider la transaction
            DB::commit();

            return redirect()->route('chercheur.espace')->with('success', 'Article enregistré avec succès.');
        } catch (\Exception $e) {
            // Si une erreur survient, annuler toutes les modifications
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage());
        }
    }



    public function modifierArticle($idArticle)
    {
        // Récupérer l'article à modifier avec ses relations
        $article = Article::with('revues', 'chercheurs')->findOrFail($idArticle);

        // Vérifier si l'utilisateur connecté est autorisé à modifier cet article
        $chercheurConnecte = Auth::user();
        if (!$article->chercheurs->contains($chercheurConnecte->idCherch)) {
            return redirect()->route('chercheur.espace')->with('error', 'Vous n\'êtes pas autorisé à modifier cet article.');
        }

        $revues = Revue::all();
        $chercheurs = Chercheur::all();

        // Retourner la vue
        return view('lab.chercheur.modifier_article', compact('article', 'revues', 'chercheurs'));

    }



    public function updateArticle(Request $request, $idArticle)
    {
        $chercheurConnecte = Auth::user(); // Récupère le chercheur connecté

        // Validation des champs
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:255', // Le titre est obligatoire
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:255',
            'chercheurs' => 'nullable|array',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
            'revue' => 'nullable|exists:revues,idRevue', // Revue est nullable
            'datePubArt' => 'nullable|date',
            'volume' => 'nullable|integer',
            'numero' => 'nullable|integer',
            'pageDebut' => 'nullable|integer|min:1',
            'pageFin' => 'nullable|integer|gte:pageDebut',
        ]);

        DB::beginTransaction();

        try {
            // Récupérer l'article à modifier
            $article = Article::findOrFail($idArticle);
            $article->titreArticle = $validatedData['titreArticle'];
            $article->resumeArticle = $validatedData['resumeArticle'] ?? $article->resumeArticle; // Conserver l'ancien résumé si non fourni
            $article->doi = $validatedData['doi'] ?? $article->doi; // Conserver l'ancien DOI si non fourni
            $article->save();

            // Mettre à jour les chercheurs et la revue si nécessaire
            DB::transaction(function () use ($article, $validatedData, $chercheurConnecte) {
                // Associer ou dissocier les chercheurs
                if (empty($validatedData['chercheurs'])) {
                    $article->chercheurs()->attach($chercheurConnecte->idCherch);
                } else {
                    $article->chercheurs()->sync($validatedData['chercheurs']); // Remplace les chercheurs existants
                }

                // Mettre à jour ou associer la revue
                if (!empty($validatedData['revue'])) {
                    $article->revues()->sync([$validatedData['revue'] => [
                        'datePubArt' => $validatedData['datePubArt'] ?? null,
                        'volume' => $validatedData['volume'] ?? null,
                        'numero' => $validatedData['numero'] ?? null,
                        'pageDebut' => $validatedData['pageDebut'] ?? null,
                        'pageFin' => $validatedData['pageFin'] ?? null,
                    ]]);
                }
            });

            // Si tout s'est bien passé, valider la transaction
            DB::commit();

            return redirect()->route('chercheur.espace')->with('success', 'Modifications enregistrées avec succès.');
        } catch (\Exception $e) {
            // Si une erreur survient, annuler toutes les modifications
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement des modifications : ' . $e->getMessage());
        }
    }




    public function rechercheArticle(Request $request)
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login'); // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
        }

        // Récupérer le terme de recherche, l'année de filtre, et le chercheur connecté
        $query = $request->input('query');
        $annee = $request->input('annee');
        $chercheurConnecte = Auth::user(); // Récupère le chercheur connecté

        // Base de la requête : récupérer tous les articles du chercheur connecté
        $articlesQuery = Article::whereHas('chercheurs', function ($queryBuilder) use ($chercheurConnecte) {
            $queryBuilder->where('chercheurs.idCherch', $chercheurConnecte->idCherch);
        });

        // Ajouter les conditions de recherche selon le terme
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                             ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                             ->orWhereHas('revues', function ($revueQuery) use ($query) {
                                 // Utilisation de 'nomRevue' à la place de 'titreRevue'
                                 $revueQuery->where('nomRevue', 'like', '%' . $query . '%');
                             })
                             // Recherche par prénom ou nom des chercheurs associés à l'article
                             ->orWhereHas('chercheurs', function ($chercheurQuery) use ($query) {
                                 $chercheurQuery->where('prenomCherch', 'like', '%' . $query . '%')
                                                ->orWhere('nomCherch', 'like', '%' . $query . '%');
                             });
            });
        }

        // Filtrer par année de publication dans la table pivot 'article_revue'
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereHas('revues', function ($queryBuilder) use ($annee) {
                $queryBuilder->whereRaw('YEAR(article_revue.datePubArt) = ?', [$annee]);
            });
        }

        // Pagination des résultats
        $articles = $articlesQuery->paginate(12);

        // Récupérer les années de publication distinctes depuis la table pivot 'article_revue'
        $annees = DB::table('article_revue')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        // Récupérer toutes les revues pour le filtre
        $revues = Revue::all();
        $chercheurs = Chercheur::all();

        // Retourner la vue avec toutes les données nécessaires
        return view('lab.chercheur.index', compact('articles', 'annees', 'revues', 'query', 'annee', 'chercheurs'));
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
            $articlesQuery->whereHas('revues', function ($queryBuilder) use ($annee) {
                $queryBuilder->whereRaw('YEAR(article_revue.datePubArt) = ?', [$annee]);
            });
        }

        // Ajouter les conditions de recherche selon le terme
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                            ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                            ->orWhereHas('revues', function ($revueQuery) use ($query) {
                                $revueQuery->where('nomRevue', 'like', '%' . $query . '%');
                            });
            });
        }

        // Pagination des résultats
        $articles = $articlesQuery->paginate(12);

        // Récupérer les années de publication distinctes depuis la table pivot 'article_revue'
        $annees = DB::table('article_revue')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        // Récupérer toutes les revues pour le filtre
        $revues = Revue::all();
        $chercheurs = Chercheur::all();


        // Retourner la vue avec toutes les données nécessaires
        return view('lab.chercheur.index', compact('articles', 'annees', 'revues', 'query', 'annee', 'chercheurs'));
    }


    public function supprimerArticle($id)
    {
        DB::beginTransaction();

        try {
            // Récupérer l'article à supprimer
            $article = Article::findOrFail($id);

            // Supprimer les relations avant de supprimer l'article
            $article->revues()->detach(); // Détacher les revues associées
            $article->chercheurs()->detach(); // Détacher les chercheurs associés
            $article->doctorants()->detach(); // Détacher les doctorants associés

            // Supprimer l'article
            $article->delete();

            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('chercheur.espace')->with('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Rediriger avec un message d'erreur si quelque chose échoue
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'article.');
        }
    }



    public function modifierProfil(Request $request)
    {
        $chercheur = Chercheur ::find(Auth::user()->idCherch); // Récupérer le chercheur connecté

        // Validation des données
        $request->validate([
            'nomCherch' => 'required|string|max:255',
            'prenomCherch' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'telCherch' => 'nullable|string|max:20',
            'emailCherch' => 'required|string|email|max:255|unique:chercheurs,emailCherch,' . $chercheur->idCherch . ',idCherch',
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Mise à jour des informations
        $chercheur->nomCherch = $request->nomCherch;
        $chercheur->prenomCherch = $request->prenomCherch;
        $chercheur->adresse = $request->adresse;
        $chercheur->telCherch = $request->telCherch;
        $chercheur->emailCherch = $request->emailCherch;

        // Gestion du mot de passe
        if ($request->filled('new_password')) {
            if ($request->filled('current_password') && Hash::check($request->current_password, $chercheur->password)) {
                $chercheur->password = Hash::make($request->new_password);
            } else {
                return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect.');
            }
        }

        // Sauvegarde
        $chercheur->save();

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }




    public function profil(){
        return view('lab.chercheur.profil');
    }
}

/*

public function enregistrerArticle(Request $request)
{
    // Valider les données du formulaire
    $validatedData = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'articles' => 'required|array|min:1', // Au moins une revue doit être associée
        'articles.*' => 'required|integer|exists:revues,idRevue', // Chaque ID de revue doit exister
        'specific_info' => 'required|array', // Informations spécifiques pour chaque revue
        'specific_info.*.PageDebut' => 'required|integer|min:1',
        'specific_info.*.PageFin' => 'required|integer|gte:specific_info.*.PageDebut', // Page de fin >= page de début
        'specific_info.*.DatePublication' => 'required|date',
        'specific_info.*.Volume' => 'nullable|integer|min:1',
        'specific_info.*.Numero' => 'nullable|integer|min:1',
    ], [
        'titre.required' => 'Le titre de l\'article est requis.',
        'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
        'description.required' => 'La description de l\'article est requise.',
        'articles.required' => 'Au moins une revue doit être sélectionnée.',
        'articles.*.exists' => 'Une des revues sélectionnées est invalide.',
        'specific_info.*.PageDebut.required' => 'La page de début est obligatoire.',
        'specific_info.*.PageFin.gte' => 'La page de fin doit être supérieure ou égale à la page de début.',
        'specific_info.*.DatePublication.required' => 'La date de publication est obligatoire.',
    ]);

    // Créer un nouvel article
    $article = Article::create([
        'titreArticle' => $validatedData['titre'],
        'resumeArticle' => $validatedData['description'],
    ]);

    // Attacher l'article au chercheur connecté
    $chercheurId = Auth::guard('chercheur')->id(); // ID du chercheur actuellement connecté
    if (!$chercheurId) {
        return redirect()->back()->with('error', 'Impossible de trouver l\'utilisateur connecté.');
    }

    // Insérer dans la table pivot chercheur_article
    DB::table('chercheur_article')->insert([
        'idCherch' => $chercheurId,
        'idArticle' => $article->idArticle,
    ]);

    // Traiter les revues associées
    $revues = $validatedData['articles']; // Liste des IDs des revues
    $specificInfos = $validatedData['specific_info']; // Détails spécifiques pour chaque revue

    foreach ($revues as $index => $revueId) {
        $specificInfo = $specificInfos[$index];

        // Ajouter les informations spécifiques dans la table pivot article_revue
        DB::table('article_revue')->insert([
            'idArticle' => $article->idArticle,
            'idRevue' => $revueId,
            'datePubArt' => $specificInfo['DatePublication'],
            'pageDebut' => $specificInfo['PageDebut'],
            'pageFin' => $specificInfo['PageFin'],
            'volume' => $specificInfo['Volume'] ?? null,
            'numero' => $specificInfo['Numero'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Rediriger avec un message de succès
    return redirect()->route('chercheur.publierArticle')->with('success', 'Article publié avec succès !');
}


*/
