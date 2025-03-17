<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AxeRecherche;
use App\Models\BdIndexation;
use App\Models\Chercheur;
use App\Models\Doctorant;
use App\Models\Grade;
use App\Models\Laboratoire;
use App\Models\Publication;
use App\Models\Theme;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeArticle;
use App\Models\TypePublication;
use App\Models\UMRI;
use App\Models\Visiteur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index()
    {
        // Statistiques principales
        $totalChercheurs = Chercheur::count();
        $totalDoctorants = Doctorant::count();
        $totalArticles = Article::count();
        $totalPublications = Publication::count();
        $totalUmris = UMRI::count();
        $totalLaboratoires = Laboratoire::count();
        $totalAxeRecherche = AxeRecherche::count();
        $totalThemes = Theme::count();
        $totalGrades = Grade::count();
        $totalBdIndexation = BdIndexation::count();
        $totalTypeArticles = TypeArticle::count();
        $totalTypePublications = TypePublication::count();

        // Articles par année
        $articlesParAnnee = DB::table('articles')
            ->selectRaw('YEAR(datePubArt) as year, COUNT(*) as count')
            ->whereNotNull('datePubArt')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('count', 'year')
            ->toArray();

        return view('lab.admin.index', compact(
            'totalChercheurs',
            'totalDoctorants',
            'totalArticles',
            'totalPublications',
            'totalUmris',
            'totalLaboratoires',
            'totalAxeRecherche',
            'totalThemes',
            'totalGrades',
            'totalBdIndexation',
            'totalTypeArticles',
            'totalTypePublications',
            'articlesParAnnee'
        ));
    }



    /**
     * Affiche la liste des articles avec filtres
     */
    public function listeArticles(Request $request)
    {
        // Récupérer les paramètres de filtre
        $typeArticleId = $request->input('typeArticle');
        $annee = $request->input('annee');
        $typeAuteur = $request->input('typeAuteur'); // 'chercheur', 'doctorant', ou null pour tous

        // Base de la requête
        $articlesQuery = Article::with([
            'chercheurs',
            'doctorants.encadrants',
            'publication.bdIndexations',
            'typeArticle'
        ]);

        // Filtre par type d'article
        if ($typeArticleId && $typeArticleId !== 'Tous') {
            $articlesQuery->where('idTypeArticle', $typeArticleId);
        }

        // Filtre par année
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereYear('datePubArt', $annee);
        }

        // Filtre par type d'auteur
        if ($typeAuteur) {
            if ($typeAuteur === 'chercheur') {
                // Articles avec chercheurs mais sans doctorants
                $articlesQuery->whereHas('chercheurs')
                             ->whereDoesntHave('doctorants');
            } elseif ($typeAuteur === 'doctorant') {
                // Articles avec doctorants
                $articlesQuery->whereHas('doctorants');
            }
        }

        // Récupérer les articles paginés
        $articles = $articlesQuery->orderBy('titreArticle', 'desc')->paginate(12);

        // Récupérer les données pour les filtres
        $typeArticles = TypeArticle::all();
        $annees = Article::selectRaw('YEAR(datePubArt) as annee')
                        ->distinct()
                        ->whereNotNull('datePubArt')
                        ->orderBy('annee', 'desc')
                        ->pluck('annee');

        $publications = Publication::orderBy('titrePub', 'asc')->get();
        $chercheurs = Chercheur::orderBy('nomCherch', 'asc')->orderBy('prenomCherch', 'asc')->get();
        $doctorants = Doctorant::orderBy('nomDoc', 'asc')->orderBy('prenomDoc', 'asc')->get();
        $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();

        return view('lab.admin.liste_article_publier', compact(
            'articles',
            'annees',
            'publications',
            'chercheurs',
            'doctorants',
            'typeArticles',
            'typeArticleId',
            'typeAuteur',
            'annee'
        ));
    }


    /**
     * Enregistre un nouvel article
     */


    public function enregistrerArticle(Request $request)
    {
        $validatedData = $request->validate([
            'titreArticle' => 'required|string|max:200',
            'lienArticle' => 'nullable|string|url',
            'resumeArticle' => 'nullable|string',
            'doi' => 'nullable|string|max:100',
            'chercheurs' => 'required|array',
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
        ], [
            'titreArticle.required' => 'Le titre de l\'article est obligatoire.',
            'chercheurs.required' => 'Au moins un chercheur doit être sélectionné.',
        ]);

        DB::beginTransaction();

        try {
            // Création de l'article
            $article = Article::create([
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

            // Si des doctorants sont sélectionnés, associer les chercheurs à ces doctorants dans la table `doctorant_article_chercheur`
            if (!empty($validatedData['doctorants'])) {
                foreach ($validatedData['doctorants'] as $doctorantId) {
                    // Pour chaque chercheur, on les associe avec le doctorant
                    foreach ($validatedData['chercheurs'] as $chercheurId) {
                        DB::table('doctorant_article_chercheur')->insert([
                            'idArticle' => $article->idArticle,
                            'idDoc' => $doctorantId,
                            'idCherch' => $chercheurId, // Associe chaque chercheur au doctorant
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
            return redirect()->back()->with('success', 'Article enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', "Une erreur est survenue : " . $e->getMessage());
        }
    }




    /**
     * Affiche le formulaire de modification d'un article
     */
    public function modifierArticle($id)
    {
            $article = Article::with(['chercheurs', 'doctorants', 'publication', 'typeArticle'])->findOrFail($id);

        // Récupérer les données pour les listes déroulantes, triées par ordre alphabétique
        $publications = Publication::orderBy('titrePub', 'asc')->get();
        $chercheurs = Chercheur::orderBy('nomCherch', 'asc')->orderBy('prenomCherch', 'asc')->get();
        $doctorants = Doctorant::with('encadrants')->orderBy('nomDoc', 'asc')->orderBy('prenomDoc', 'asc')->get();
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

            return view('lab.admin.modifier_article', compact(
                'article',
                'publications',
            'chercheurs',
            'doctorants',
                'typeArticles',
            'chercheurIds',
            'doctorantIds'
            ));
    }



    /**
     * Met à jour un article existant
     */
    public function updateArticle(Request $request, $id)
    {
        // Validation des données
            $validatedData = $request->validate([
                'titreArticle' => 'required|string|max:200',
                'lienArticle' => 'nullable|string|url',
                'resumeArticle' => 'nullable|string',
                'doi' => 'nullable|string|max:100',
                'chercheurs' => 'required|array',
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

            // Si des doctorants sont sélectionnés
            if (!empty($validatedData['doctorants'])) {
                foreach ($validatedData['doctorants'] as $doctorantId) {
                    // Pour chaque chercheur, créer une association avec le doctorant
                    foreach ($validatedData['chercheurs'] as $chercheurId) {
                        // Insérer dans la table de liaison avec les trois colonnes
                        DB::table('doctorant_article_chercheur')->insert([
                            'idArticle' => $article->idArticle,
                            'idDoc' => $doctorantId,
                            'idCherch' => $chercheurId
                        ]);
                    }
                }
            } else {
                // Si aucun doctorant n'est sélectionné, associer uniquement les chercheurs à l'article
                $chercheurData = [];
                foreach ($validatedData['chercheurs'] as $index => $chercheurId) {
                    $chercheurData[$chercheurId] = ['rang' => $index + 1];
                }
                // Attacher les chercheurs à l'article avec leur rang
                $article->chercheurs()->attach($chercheurData);
            }

            DB::commit();
            return redirect()->route('admin.listeArticles')
                ->with('success', 'Article mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }



    public function supprimerArticle($id)
    {
        try {
            $article = Article::findOrFail($id);

            DB::beginTransaction();

            // Détacher les relations many-to-many
            $article->chercheurs()->detach();
            $article->doctorants()->detach();

            // Supprimer l'article
            $article->delete();

            DB::commit();

            return redirect()->route('admin.listeArticles')
                ->with('success', 'Article supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }


    public function rechercherEtFiltrerArticles(Request $request)
    {
        $query = trim($request->input('query'));
        $annee = $request->input('annee');

        // Initialisation de la requête avec les relations nécessaires
        $articlesQuery = Article::query()
            ->with(['chercheurs', 'doctorants', 'publication', 'typeArticle', 'doctorants.encadrants',
            'publication.bdIndexations']);

        // Filtrer par année si sélectionnée
        if ($annee && $annee !== 'Tous') {
            $articlesQuery->whereYear('datePubArt', $annee);
        }

        // Appliquer la recherche si un terme est entré
        if ($query) {
            $articlesQuery->where(function ($queryBuilder) use ($query) {
                // Recherche dans les colonnes de l'article
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%');

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

        // Récupération des articles et pagination
        $articles = $articlesQuery->orderBy('titreArticle', 'asc')->paginate(12);

        // Récupérer les filtres pour la vue
        $annees = DB::table('articles')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        $publications = Publication::orderBy('titrePub', 'asc')->get();
        $chercheurs = Chercheur::orderBy('nomCherch', 'asc')->orderBy('prenomCherch', 'asc')->get();
        $doctorants = Doctorant::orderBy('nomDoc', 'asc')->orderBy('prenomDoc', 'asc')->get();
        $typeArticles = TypeArticle::orderBy('nomTypeArticle', 'asc')->get();
        $typeArticleId = null;
        $typeAuteur = null;

        return view('lab.admin.liste_article_publier', compact(
            'articles',
            'annees',
            'publications',
            'query',
            'annee',
            'chercheurs',
            'doctorants',
            'typeArticles',
            'typeArticleId',
            'typeAuteur'
        ));
    }



    public function profil() {
        $chercheurConnecter = Auth::user();
        $chercheur = Chercheur::findOrFail($chercheurConnecter->idCherch);

        return view('lab.admin.profil', compact('chercheur'));
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
                    return redirect()->route('admin.profil')
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
            return redirect()->route('admin.profil')
                ->with('success', 'Profil mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.profil')
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du profil : ' . $e->getMessage());
        }
    }

}

