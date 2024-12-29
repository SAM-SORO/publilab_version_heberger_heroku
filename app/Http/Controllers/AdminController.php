<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AxeRecherche;
use App\Models\BdIndexation;
use App\Models\Chercheur;
use App\Models\Doctorant;
use App\Models\Grade;
use App\Models\Laboratoire;
use App\Models\Revue;
use App\Models\Theme;
use App\Models\UMRI;
use App\Models\Visiteur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index()
    {
        @dd("ici");
        $nombreChercheurs = Chercheur::count();
        $nombreVisiteurs = Visiteur::count();
        $nombreArticles = Article::count();
        $nombreRevues = Revue::count();
        $nombreUmris = UMRI::count();
        $nombreLaboratoires = Laboratoire::count();
        $nombreAxeRecherche = AxeRecherche::count();
        $nombreThemes = Theme::count();
        $nombreDoctorant = Doctorant::count();
        $nombreGrade = Grade::count();
        $nombreBdIndexation = BdIndexation::count();


        return view('lab.admin.index', compact('nombreChercheurs', 'nombreVisiteurs', 'nombreArticles', 'nombreRevues', 'nombreUmris', 'nombreLaboratoires', 'nombreAxeRecherche', 'nombreThemes' , 'nombreDoctorant' , 'nombreGrade', 'nombreBdIndexation'));
    }



    public function listeArticles(Request $request)
    {
        $query = $request->input('query'); // Terme de recherche
        $annee = $request->input('annee'); // Filtre par année
        $revue = $request->input('revue'); // Filtre par revue

        // Base de la requête pour récupérer tous les articles
        $articlesQuery = Article::query();

        // Recherche par titre, résumé ou revue
        if ($query) {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('titreArticle', 'like', '%' . $query . '%')
                ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                ->orWhereHas('revues', function ($revueQuery) use ($query) {
                    $revueQuery->where('nomRevue', 'like', '%' . $query . '%');
                });
            });
        }

        // Filtrage par année
        if ($annee) {
            $articlesQuery->whereHas('revues', function ($q) use ($annee) {
                $q->whereYear('datePubArt', $annee);
            });
        }

        // Filtrage par revue
        if ($revue) {
            $articlesQuery->whereHas('revues', function ($q) use ($revue) {
                $q->where('idRevue', $revue);
            });
        }

        // Tri par `created_at` en ordre décroissant
        $articlesQuery->orderByDesc('created_at'); // Tri par `created_at` en ordre décroissant

        // Récupération des articles paginés
        $articles = $articlesQuery->paginate(6);

        // Récupérer les années disponibles
        $annees = DB::table('article_revue')
                    ->selectRaw('YEAR(datePubArt) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        // Récupérer toutes les revues
        $revues = Revue::all();

        // Récupérer tous les chercheurs
        $chercheurs = Chercheur::all();

        return view('lab.admin.liste_article_publier', compact('articles', 'annees', 'revues', 'query', 'annee', 'revue', 'chercheurs'));
    }


    public function enregistrerArticle(Request $request)
    {
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

            // Associer les chercheurs s'ils existent
            if (!empty($validatedData['chercheurs'])) {
                $article->chercheurs()->attach($validatedData['chercheurs']);
            }

            // Associer la revue avec des champs optionnels dans la table pivot
            if (!empty($validatedData['revue'])) {
                $article->revues()->attach($validatedData['revue'], [
                    'datePubArt' => $validatedData['datePubArt'] ?? null,
                    'volume' => $validatedData['volume'] ?? null,
                    'numero' => $validatedData['numero'] ?? null,
                    'pageDebut' => $validatedData['pageDebut'] ?? null,
                    'pageFin' => $validatedData['pageFin'] ?? null,
                ]);
            }

            // Si tout s'est bien passé, valider la transaction
            DB::commit();

            return redirect()->route('admin.liste-articles')->with('success', 'Article enregistré avec succès.');
        } catch (\Exception $e) {
            // Si une erreur survient, annuler toutes les modifications
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage());
        }
    }




    //envoie sur la vue de mise a jour
    public function modifierArticle($idArticle)
    {
        $article = Article::findOrFail($idArticle);
        $chercheurs = Chercheur::all();  // Récupérer tous les chercheurs
        $revues = Revue::all();  // Récupérer toutes les revues

        return view('lab.admin.modifier_article', compact('article', 'chercheurs', 'revues'));
    }


    public function updateArticle(Request $request, $id)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données avec uniquement titreArticle requis
            $validatedData = $request->validate([
                'titreArticle' => 'required|string|max:255',
                'resumeArticle' => 'nullable|string',
                'doi' => 'nullable|string|max:255',
                'revue' => 'nullable|integer|exists:revues,idRevue', // Revues désormais nullable
                'datePubArt' => 'nullable|date', // Date de publication nullable
                'volume' => 'nullable|integer|min:1',
                'numero' => 'nullable|integer|min:1',
                'pageDebut' => 'nullable|integer|min:1',
                'pageFin' => 'nullable|integer|gte:pageDebut',
                'chercheurs' => 'nullable|array', // Liste de chercheurs nullable
                'chercheurs.*' => 'integer|exists:chercheurs,idCherch',
            ], [
                'titreArticle.required' => 'Le titre de l\'article est obligatoire.',
                'titreArticle.string' => 'Le titre de l\'article doit être une chaîne de caractères.',
                'titreArticle.max' => 'Le titre de l\'article ne peut pas dépasser 255 caractères.',

                'resumeArticle.string' => 'Le résumé de l\'article doit être une chaîne de caractères.',

                'doi.string' => 'Le DOI de l\'article doit être une chaîne de caractères.',
                'doi.max' => 'Le DOI de l\'article ne peut pas dépasser 255 caractères.',

                'revue.integer' => 'La revue doit être un identifiant valide.',
                'revue.exists' => 'La revue sélectionnée n\'existe pas.',

                'datePubArt.date' => 'La date de publication doit être une date valide.',

                'volume.integer' => 'Le volume de l\'article doit être un nombre entier.',
                'volume.min' => 'Le volume de l\'article ne peut pas être inférieur à 1.',

                'numero.integer' => 'Le numéro de l\'article doit être un nombre entier.',
                'numero.min' => 'Le numéro de l\'article ne peut pas être inférieur à 1.',

                'pageDebut.integer' => 'La page de début doit être un nombre entier.',
                'pageDebut.min' => 'La page de début ne peut pas être inférieure à 1.',

                'pageFin.integer' => 'La page de fin doit être un nombre entier.',
                'pageFin.gte' => 'La page de fin doit être supérieure ou égale à la page de début.',

                'chercheurs.array' => 'Les chercheurs doivent être une liste valide.',
                'chercheurs.*.integer' => 'Chaque ID de chercheur doit être un nombre valide.',
                'chercheurs.*.exists' => 'Un ou plusieurs chercheurs sélectionnés n\'existent pas.',
            ]);

            // Récupérer l'article existant
            $article = Article::findOrFail($id);

            // Mise à jour des champs de l'article
            $article->update([
                'titreArticle' => $validatedData['titreArticle'],
                'resumeArticle' => $validatedData['resumeArticle'] ?? null,
                'doi' => $validatedData['doi'] ?? null,
            ]);

            // Mise à jour des informations spécifiques à la revue (si revue existe)
            if (!empty($validatedData['revue'])) {
                $article->revues()->sync([
                    $validatedData['revue'] => [
                        'datePubArt' => $validatedData['datePubArt'] ?? null,
                        'volume' => $validatedData['volume'] ?? null,
                        'numero' => $validatedData['numero'] ?? null,
                        'pageDebut' => $validatedData['pageDebut'] ?? null,
                        'pageFin' => $validatedData['pageFin'] ?? null,
                    ],
                ]);
            }

            // Mise à jour des chercheurs associés à l'article
            if (!empty($validatedData['chercheurs'])) {
                $article->chercheurs()->sync($validatedData['chercheurs']);
            }

            // Commit de la transaction
            DB::commit();

            // Retourner une réponse ou rediriger
            return redirect()->route('admin.liste-articles')->with('success', 'Article mis à jour avec succès.');

        } catch (\Exception $e) {
            // Si une erreur survient, annuler la transaction
            DB::rollBack();

            // Optionnel: Log de l'erreur
            // \Log::error("Erreur lors de la mise à jour de l'article (ID: $id): " . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de l\'article.' . $e->getMessage());
        }
    }



    public function supprimerArticle($id)
    {
        // Démarrer une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // Récupérer l'article à supprimer
            $article = Article::findOrFail($id);

            // Supprimer les relations avec les revues
            $article->revues()->detach();

            // Supprimer les relations avec les chercheurs
            $article->chercheurs()->detach();

            // Supprimer les relations avec les doctorants via les encadrants
            $article->doctorants()->detach();

            // Supprimer l'article
            $article->delete();

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.liste-articles')->with('success', 'Article supprimé avec succès.');

        } catch (\Exception $e) {
            // Rollback de la transaction en cas d'erreur
            DB::rollBack();

            // Optionnel : log de l'erreur
            // \Log::error("Erreur lors de la suppression de l'article (ID: $id): " . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'article.');
        }
    }



    public function rechercherEtFiltrerArticles(Request $request)
    {
        $query = $request->input('query'); // Recherche texte
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
                $queryBuilder->where('titreArticle', 'like', '%' . $query . '%')
                            ->orWhere('resumeArticle', 'like', '%' . $query . '%')
                            ->orWhereHas('revues', function ($revueQuery) use ($query) {
                                $revueQuery->where('nomRevue', 'like', '%' . $query . '%');
                            })
                            // Recherche par prénom ou nom des chercheurs
                            ->orWhereHas('chercheurs', function ($chercheurQuery) use ($query) {
                                $chercheurQuery->where('prenomCherch', 'like', '%' . $query . '%')
                                               ->orWhere('nomCherch', 'like', '%' . $query . '%');
                            });
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
        $chercheurs = Chercheur::all();

        return view('lab.admin.liste_article_publier', compact('articles', 'annees', 'revues', 'query', 'annee', 'chercheurs'));
    }




    public function profil(){
        return view('lab.admin.profil');
    }


}
