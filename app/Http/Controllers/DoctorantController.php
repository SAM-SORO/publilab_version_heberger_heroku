<?php

namespace App\Http\Controllers;

use App\Models\Doctorant;
use App\Models\Article;
use App\Models\UMRI;
use App\Models\Theme;
use App\Models\TypeArticle;
use App\Models\Chercheur;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorantController extends Controller
{

    //ce que le doctorant connecter peut faire

    public function index()
    {
        // Récupérer le doctorant connecté
        $doctorantConnecte = auth()->user();

        // Vérifier si un doctorant est connecté
        if (!$doctorantConnecte) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Compter le nombre d'articles du doctorant
        $NbreArticles = Article::whereHas('doctorants', function ($query) use ($doctorantConnecte) {
            $query->where('doctorant_article_chercheur.idDoc', $doctorantConnecte->idDoc);
        })->count();

        // Récupérer les articles du doctorant
        $articles = Article::whereHas('doctorants', function ($query) use ($doctorantConnecte) {
            $query->where('doctorant_article_chercheur.idDoc', $doctorantConnecte->idDoc);
        })->with(['publication', 'typeArticle'])->get();

        return view('lab.doctorant.index', compact('doctorantConnecte', 'NbreArticles', 'articles'));
    }

    public function listeArticles(Request $request)
    {
        try {
            $doctorant = Auth::user();

            // Initialiser la requête de base pour récupérer les articles du doctorant connecté
            $articlesQuery = Article::with(['publication', 'typeArticle', 'chercheurs', 'doctorants'])
                ->whereHas('doctorants', function($q) use ($doctorant) {
                    $q->where('doctorants.idDoc', $doctorant->idDoc);
                });

            // Récupérer les paramètres de filtre
            $query = $request->input('query');
            $annee = $request->input('annee');
            $typeArticleId = $request->input('typeArticle');

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

            // Récupérer les articles filtrés et paginés
            $articles = $articlesQuery->orderBy('created_at', 'desc')->paginate(12);

            // Conserver les paramètres de filtre dans la pagination
            $articles->appends([
                'query' => $query,
                'annee' => $annee,
                'typeArticle' => $typeArticleId
            ]);

            // Récupérer les années distinctes pour le filtre
            $annees = DB::table('articles')
                ->selectRaw('YEAR(datePubArt) as year')
                ->distinct()
                ->whereNotNull('datePubArt')
                ->orderBy('year', 'desc')
                ->pluck('year');

            // Récupérer les données pour les autres filtres et formulaires
            $publications = Publication::orderBy('titrePub', 'asc')->get();
            $chercheurs = Chercheur::orderBy('nomCherch', 'asc')
                        ->orderBy('prenomCherch', 'asc')
                        ->get();
            $doctorants = Doctorant::orderBy('nomDoc', 'asc')
                        ->orderBy('prenomDoc', 'asc')
                        ->get();
            $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();

            // Variables pour les filtres actifs
            $typeArticleId = $typeArticleId ?? null;

            return view('lab.doctorant.liste_article', compact(
                'articles',
                'annees',
                'publications',
                'typeArticles',
                'chercheurs',
                'doctorants',
                'query',
                'annee',
                'typeArticleId'
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
            'chercheurs' => 'required|array',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
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

            // Récupérer le doctorant connecté
            $doctorantConnecte = Auth::user();

            // S'assurer que des chercheurs sont sélectionnés
            if (empty($validatedData['chercheurs'])) {
                // Si aucun chercheur n'est sélectionné, on ne peut pas créer l'entrée dans doctorant_article_chercheur
                DB::rollback();
                return redirect()->back()->with('error', "Vous devez sélectionner au moins un chercheur pour cet article.");
            }

            // Pour chaque chercheur sélectionné, créer une entrée dans doctorant_article_chercheur
            foreach ($validatedData['chercheurs'] as $chercheurId) {
                DB::table('doctorant_article_chercheur')->insert([
                    'idArticle' => $article->idArticle,
                    'idDoc' => $doctorantConnecte->idDoc,
                    'idCherch' => $chercheurId
                ]);
            }

            DB::commit();
            return redirect()->route('doctorant.listeArticles')->with('success', 'Article enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', "Une erreur est survenue : " . $e->getMessage());
        }
    }

    public function modifierArticle($idArticle)
    {
        try {
            // Récupérer l'article avec ses relations
            $article = Article::with(['publication', 'chercheurs', 'doctorants'])->findOrFail($idArticle);

            // Vérifier si le doctorant connecté est autorisé à modifier cet article
            $doctorantConnecte = Auth::user();
            $isAuthorized = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->where('idDoc', $doctorantConnecte->idDoc)
                ->exists();

            if (!$isAuthorized) {
                return redirect()->route('doctorant.listeArticles')
                    ->with('error', 'Vous n\'êtes pas autorisé à modifier cet article.');
            }

            // Récupérer les données nécessaires pour le formulaire
            $publications = Publication::orderBy('titrePub', 'asc')->get();
            $chercheurs = Chercheur::orderBy('nomCherch', 'asc')
                        ->orderBy('prenomCherch', 'asc')
                        ->get();
            $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();

            // Récupérer les IDs des chercheurs associés à cet article pour ce doctorant
            $articleChercheurs = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->where('idDoc', $doctorantConnecte->idDoc)
                ->pluck('idCherch')
                ->toArray();

            return view('lab.doctorant.modifier_article', compact(
                'article',
                'publications',
                'chercheurs',
                'typeArticles',
                'articleChercheurs'
            ));

        } catch (\Exception $e) {
            return redirect()->route('doctorant.listeArticles')
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function updateArticle(Request $request, $idArticle)
    {
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:200',
            'lienArticle' => 'nullable|string|url',
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:100',
            'chercheurs' => 'required|array',
            'chercheurs.*' => 'exists:chercheurs,idCherch',
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
            $article = Article::findOrFail($idArticle);

            // Vérifier si le doctorant connecté est autorisé à modifier cet article
            $doctorantConnecte = Auth::user();
            $isAuthorized = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->where('idDoc', $doctorantConnecte->idDoc)
                ->exists();

            if (!$isAuthorized) {
                return redirect()->route('doctorant.listeArticles')
                    ->with('error', 'Vous n\'êtes pas autorisé à modifier cet article.');
            }

            // Mettre à jour les informations de l'article
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

            // Supprimer les anciennes relations doctorant-article-chercheur
            DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->where('idDoc', $doctorantConnecte->idDoc)
                ->delete();

            // Créer les nouvelles relations doctorant-article-chercheur
            foreach ($validatedData['chercheurs'] as $chercheurId) {
                DB::table('doctorant_article_chercheur')->insert([
                    'idArticle' => $article->idArticle,
                    'idDoc' => $doctorantConnecte->idDoc,
                    'idCherch' => $chercheurId
                ]);
            }

            DB::commit();
            return redirect()->route('doctorant.listeArticles')
                ->with('success', 'Article modifié avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }



    /// Ce que l'administrateur peut faire

    public function listeDoctorants()
    {
        // Ajouter UMRI aux données récupérées
        $doctorants = Doctorant::with(['theme.axeRecherche', 'encadrants', 'articles', 'umri'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $themes = Theme::where('etatAttribution', false)->get();
        $chercheurs = Chercheur::all();
        $umris = UMRI::all(); // Ajouter la récupération des UMRI

        return view('lab.admin.liste_doctorant', compact('doctorants', 'themes', 'chercheurs', 'umris'));
    }

    public function create(Request $request)
    {
        // Ajouter la validation pour UMRI
        $validated = $request->validate([
            'nomDoc' => 'required|string|max:255',
            'prenomDoc' => 'required|string|max:255',
            'matriculeDoc' => 'required|string|max:50|unique:doctorants,matriculeDoc',
            'password' => 'required|string|min:6|confirmed',
            'idUMRI' => 'required|exists:umris,idUMRI', // Ajout de la validation UMRI
            'genreDoc' => 'nullable|in:M,F',
            'emailDoc' => 'nullable|email|unique:doctorants,emailDoc',
            'telDoc' => 'nullable|string|max:20',
            'idTheme' => 'nullable|exists:themes,idTheme',
            'encadrants' => 'nullable|array',
            'encadrants.*' => 'exists:chercheurs,idCherch'
        ], [
            'nomDoc.required' => 'Le nom est obligatoire',
            'prenomDoc.required' => 'Le prénom est obligatoire',
            'matriculeDoc.required' => 'Le matricule est obligatoire',
            'matriculeDoc.unique' => 'Ce matricule existe déjà',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'emailDoc.unique' => 'Cet email existe déjà',
            'emailDoc.email' => 'Format d\'email invalide'
        ]);

        DB::beginTransaction();

        try {
            // Créer le doctorant avec l'UMRI
            $doctorant = Doctorant::create([
                'nomDoc' => $validated['nomDoc'],
                'prenomDoc' => $validated['prenomDoc'],
                'matriculeDoc' => $validated['matriculeDoc'],
                'genreDoc' => $validated['genreDoc'] ?? null,
                'emailDoc' => $validated['emailDoc'] ?? null,
                'telDoc' => $validated['telDoc'] ?? null,
                'password' => Hash::make($validated['password']),
                'idTheme' => $validated['idTheme'] ?? null,
                'idUMRI' => $validated['idUMRI'] // Ajout de l'UMRI
            ]);

            // Mettre à jour l'état d'attribution du thème si un thème est sélectionné
            if (isset($validated['idTheme'])) {
                $theme = Theme::findOrFail($validated['idTheme']);
                $theme->update(['etatAttribution' => true]);
            }

            // Attacher les encadrants si présents
            if (isset($validated['encadrants']) && !empty($validated['encadrants'])) {
                foreach ($validated['encadrants'] as $encadrantId) {
                    $doctorant->encadrants()->attach($encadrantId, [
                        'dateDebut' => now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.listeDoctorants')
                ->with('success', 'Doctorant créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Récupérer les données nécessaires
            $doctorants = Doctorant::with(['theme.axeRecherche', 'encadrants', 'articles', 'umri'])
                ->orderByDesc('created_at')
                ->paginate(10);
            $themes = Theme::where('etatAttribution', false)->get();
            $chercheurs = Chercheur::all();
            $umris = UMRI::all();

            return redirect()->back()
                ->withInput()
                ->with(compact('doctorants', 'themes', 'chercheurs', 'umris'))
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $doctorant = Doctorant::findOrFail($id);

            // Libérer le thème associé
            if ($doctorant->idTheme) {
                $theme = Theme::find($doctorant->idTheme);
                if ($theme && !$theme->doctorants()->where('idDoc', '!=', $id)->exists()) {
                    $theme->update(['etatAttribution' => false]);
                }
            }

            // Supprimer le doctorant
            $doctorant->delete();

            // Récupérer les données nécessaires
            $doctorants = Doctorant::with(['theme.axeRecherche', 'encadrants', 'articles', 'umri'])
                ->orderByDesc('created_at')
                ->paginate(10);
            $themes = Theme::where('etatAttribution', false)->get();
            $chercheurs = Chercheur::all();
            $umris = UMRI::all();

            return redirect()->route('admin.listeDoctorants')
                ->with(compact('doctorants', 'themes', 'chercheurs', 'umris'))
                ->with('success', 'Doctorant supprimé avec succès.');

        } catch (\Exception $e) {
            return redirect()->route('admin.listeDoctorants')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }


    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // Si la recherche est vide, retourner tous les doctorants
            if (empty($query)) {
                return redirect()->route('admin.listeDoctorants');
            }

            $doctorants = Doctorant::where(function($q) use ($query) {
                // Recherche sur le nom complet
                if (str_contains($query, ' ')) {
                    $parts = explode(' ', $query);
                    $nom = $parts[0];
                    $prenom = $parts[1] ?? '';

                    $q->where(function($subQ) use ($nom, $prenom) {
                        // Recherche exacte sur nom et prénom
                        $subQ->where(function($innerQ) use ($nom, $prenom) {
                            $innerQ->where('nomDoc', 'like', '%' . $nom . '%')
                                   ->where('prenomDoc', 'like', '%' . $prenom . '%');
                        })
                        // OU recherche inversée (prénom nom)
                        ->orWhere(function($innerQ) use ($nom, $prenom) {
                            $innerQ->where('prenomDoc', 'like', '%' . $nom . '%')
                                   ->where('nomDoc', 'like', '%' . $prenom . '%');
                        });
                    });
                } else {
                    // Recherche simple sur un seul terme
                    $q->where('nomDoc', 'like', '%' . $query . '%')
                      ->orWhere('prenomDoc', 'like', '%' . $query . '%')
                      ->orWhere('matriculeDoc', 'like', '%' . $query . '%')
                      ->orWhere('emailDoc', 'like', '%' . $query . '%')
                      // Recherche dans les thèmes
                      ->orWhereHas('theme', function($subQ) use ($query) {
                          $subQ->where('intituleTheme', 'like', '%' . $query . '%');
                      })
                      // Recherche dans les encadrants
                      ->orWhereHas('encadrants', function($subQ) use ($query) {
                          $subQ->where(DB::raw("CONCAT(nomCherch, ' ', prenomCherch)"), 'like', '%' . $query . '%')
                               ->orWhere(DB::raw("CONCAT(prenomCherch, ' ', nomCherch)"), 'like', '%' . $query . '%');
                      });
                }
            })
            ->with(['theme.axeRecherche', 'encadrants', 'articles', 'umri'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

            // Récupérer les données nécessaires pour le formulaire
            $themes = Theme::where('etatAttribution', false)->get();
            $chercheurs = Chercheur::all();
            $umris = UMRI::all();

            return view('lab.admin.liste_doctorant', compact('doctorants', 'themes', 'chercheurs', 'umris', 'query'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeDoctorants')
                ->with('error', 'Erreur lors de la recherche : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Récupérer le doctorant avec toutes ses relations, y compris UMRI
            $doctorant = Doctorant::with(['theme', 'encadrants', 'articles', 'umri'])->findOrFail($id);

            // Récupérer les thèmes disponibles
            $themes = Theme::where('etatAttribution', false)
                ->orWhere('idTheme', $doctorant->idTheme)
                ->orWhereHas('doctorants', function($query) use ($id) {
                    $query->where('idDoc', $id);
                })
                ->get();

            // Récupérer tous les chercheurs
            $chercheurs = Chercheur::all();

            // Récupérer toutes les UMRI
            $umris = UMRI::all();

            // Récupérer les IDs des encadrants actuels
            $encadrantsIds = $doctorant->encadrants->pluck('idCherch')->toArray();

            return view('lab.admin.modifier_doctorant', compact(
                'doctorant',
                'themes',
                'chercheurs',
                'encadrantsIds',
                'umris' // Ajout des UMRI dans les données envoyées à la vue
            ));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeDoctorants')
                ->with('error', 'Doctorant non trouvé : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Ajouter la validation pour UMRI
        $rules = [
            'nomDoc' => 'required|string|max:255',
            'prenomDoc' => 'required|string|max:255',
            'matriculeDoc' => 'required|string|max:50|unique:doctorants,matriculeDoc,' . $id . ',idDoc',
            'idUMRI' => 'required|exists:umris,idUMRI', // Ajout de la validation UMRI
            'genreDoc' => 'nullable|in:M,F',
            'emailDoc' => 'nullable|email|unique:doctorants,emailDoc,' . $id . ',idDoc',
            'telDoc' => 'nullable|string|max:20',
            'idTheme' => 'nullable|exists:themes,idTheme',
            'encadrants' => 'nullable|array',
            'encadrants.*' => 'exists:chercheurs,idCherch'
        ];

        // Ajouter la validation du mot de passe s'il est fourni
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $validated = $request->validate($rules, [
            'nomDoc.required' => 'Le nom est obligatoire',
            'prenomDoc.required' => 'Le prénom est obligatoire',
            'matriculeDoc.required' => 'Le matricule est obligatoire',
            'matriculeDoc.unique' => 'Ce matricule est déjà utilisé par un autre doctorant',
            'emailDoc.unique' => 'Cet email est déjà utilisé par un autre doctorant',
            'emailDoc.email' => 'Format d\'email invalide',
            'password.confirmed' => 'Les mots de passe ne correspondent pas'
        ]);

        try {
            DB::beginTransaction();

            $doctorant = Doctorant::findOrFail($id);
            $ancienThemeId = $doctorant->idTheme;

            // Préparer les données de mise à jour
            $dataToUpdate = [
                'nomDoc' => $validated['nomDoc'],
                'prenomDoc' => $validated['prenomDoc'],
                'matriculeDoc' => $validated['matriculeDoc'],
                'genreDoc' => $validated['genreDoc'] ?? null,
                'emailDoc' => $validated['emailDoc'] ?? null,
                'telDoc' => $validated['telDoc'] ?? null,
                'idTheme' => $validated['idTheme'] ?? null,
                'idUMRI' => $validated['idUMRI'] // Ajout de l'UMRI
            ];

            // Mettre à jour le mot de passe si fourni
            if ($request->filled('password')) {
                $dataToUpdate['password'] = Hash::make($validated['password']);
            }

            // Mise à jour des informations
            $doctorant->update($dataToUpdate);

            // Gestion du thème
            if ($ancienThemeId !== ($validated['idTheme'] ?? null)) {
                // Libérer l'ancien thème si nécessaire
                if ($ancienThemeId) {
                    $ancienTheme = Theme::find($ancienThemeId);
                    if ($ancienTheme && !$ancienTheme->doctorants()->where('idDoc', '!=', $id)->exists()) {
                        $ancienTheme->update(['etatAttribution' => false]);
                    }
                }

                // Marquer le nouveau thème comme attribué
                if (isset($validated['idTheme'])) {
                    Theme::findOrFail($validated['idTheme'])->update(['etatAttribution' => true]);
                }
            }

            // Gestion des encadrants
            if (isset($validated['encadrants'])) {
                $doctorant->encadrants()->sync($validated['encadrants']);
            }

            DB::commit();
            return redirect()->route('admin.listeDoctorants')
                ->with('success', 'Doctorant modifié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Récupérer les données nécessaires
            $doctorants = Doctorant::with(['theme.axeRecherche', 'encadrants', 'articles', 'umri'])
                ->orderByDesc('created_at')
                ->paginate(10);
            $themes = Theme::where('etatAttribution', false)->get();
            $chercheurs = Chercheur::all();
            $umris = UMRI::all();

            return redirect()->back()
                ->withInput()
                ->with(compact('doctorants', 'themes', 'chercheurs', 'umris'))
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function deleteArticle($idArticle)
    {
        try {
            // Récupérer l'article
            $article = Article::findOrFail($idArticle);

            // Vérifier si le doctorant connecté est autorisé à supprimer cet article
            $doctorantConnecte = Auth::user();
            $isAuthorized = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->where('idDoc', $doctorantConnecte->idDoc)
                ->exists();

            if (!$isAuthorized) {
                return redirect()->route('doctorant.listeArticles')
                    ->with('error', 'Vous n\'êtes pas autorisé à supprimer cet article.');
            }

            // Démarrer une transaction
            DB::beginTransaction();

            // Supprimer les relations dans doctorant_article_chercheur
            DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->where('idDoc', $doctorantConnecte->idDoc)
                ->delete();

            // Vérifier si l'article est encore référencé par d'autres doctorants ou chercheurs
            $hasOtherDoctorants = DB::table('doctorant_article_chercheur')
                ->where('idArticle', $idArticle)
                ->exists();

            $hasOtherChercheurs = DB::table('chercheur_article')
                ->where('idArticle', $idArticle)
                ->exists();

            // Si l'article n'est plus référencé par personne, le supprimer
            if (!$hasOtherDoctorants && !$hasOtherChercheurs) {
                // Supprimer l'article
                $article->delete();
            }

            // Valider la transaction
            DB::commit();

            return redirect()->route('doctorant.listeArticles')
                ->with('success', 'Article supprimé avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            return redirect()->route('doctorant.listeArticles')
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }

    public function rechercheArticle(Request $request)
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer une recherche.');
        }

        $doctorantConnecte = Auth::user();
        $query = $request->input('query');
        $annee = $request->input('annee');

        // Base de la requête : récupérer tous les articles du doctorant connecté
        $articlesQuery = Article::with(['publication', 'typeArticle', 'chercheurs', 'doctorants'])
            ->whereHas('doctorants', function($q) use ($doctorantConnecte) {
                $q->where('doctorants.idDoc', $doctorantConnecte->idDoc);
            });

        // Appliquer la recherche si un terme est entré
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

        // Récupérer les articles filtrés et paginés
        $articles = $articlesQuery->orderBy('created_at', 'desc')->paginate(12);

        // Conserver les paramètres de filtre dans la pagination
        $articles->appends([
            'query' => $query,
            'annee' => $annee
        ]);

        // Récupérer les années distinctes pour le filtre
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

        // Retourner la vue avec toutes les données nécessaires
        return view('lab.doctorant.liste_article', compact(
            'articles',
            'annees',
            'publications',
            'typeArticles',
            'chercheurs',
            'doctorants',
            'query',
            'annee',
            'typeArticleId'
        ));
    }

    public function profil()
    {
        $doctorant = Auth::guard('doctorant')->user();
        return view('lab.doctorant.profil', compact('doctorant'));
    }

    public function modifierProfil(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nomDoc' => 'required|string|max:255',
            'prenomDoc' => 'required|string|max:255',
            'genreDoc' => 'nullable|in:M,F',
            'matriculeDoc' => 'required|string|max:20',
            'emailDoc' => 'required|email|max:100|unique:doctorants,emailDoc,' . auth()->user()->idDoc . ',idDoc',
            'telDoc' => 'nullable|string|max:15',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'new_password_confirmation' => 'nullable|required_with:new_password',
        ]);

        DB::beginTransaction();

        try {
            $doctorant = Doctorant::findOrFail(auth()->guard('doctorant')->user()->idDoc);

            // Mise à jour des informations de base
            $updateData = collect($validated)->except(['current_password', 'new_password', 'new_password_confirmation'])->toArray();

            // Gestion du mot de passe
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $doctorant->password)) {
                    return redirect()->route('doctorant.profil')
                        ->withInput()
                        ->with('error', 'Le mot de passe actuel est incorrect.');
                }

                if ($request->filled('new_password')) {
                    $updateData['password'] = Hash::make($request->new_password);
                }
            }

            // Mise à jour des données
            $doctorant->fill($updateData)->save();

            DB::commit();
            return redirect()->route('doctorant.profil')
                ->with('success', 'Profil mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('doctorant.profil')
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du profil : ' . $e->getMessage());
        }
    }
}

