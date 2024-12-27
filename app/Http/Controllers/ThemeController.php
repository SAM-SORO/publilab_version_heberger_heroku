<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\AxeRecherche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    // Affichage des thèmes
    public function index()
    {
        // Récupérer les thèmes triés en ordre décroissant (par exemple, selon la date de création)
        $themes = Theme::with('axeRecherche')
            ->orderByDesc('created_at') // Trier par la colonne created_at en ordre décroissant
            ->paginate(10); // Pagination avec 10 résultats par page

        // Récupérer tous les axes de recherche pour l'affichage dans la vue
        $axesRecherches = AxeRecherche::all();

        // Retourner la vue avec les données
        return view('lab.admin.liste_theme', compact('themes', 'axesRecherches'));
    }


    public function create(Request $request)
    {
        // Validation des données reçues
        $validated = $request->validate([
            'intituleTheme' => 'required|string|max:255', // Intitulé requis
            'descTheme' => 'nullable|string|max:500',    // Description modifiée pour être nullable
            'idAxeRech' => 'required|exists:axe_recherches,idAxeRech', // Validation pour un axe de recherche valide
        ],[
            'intituleTheme.required' => 'L\'intitulé du thème est obligatoire.',
            'intituleTheme.string' => 'L\'intitulé du thème doit être une chaîne de caractères.',
            'intituleTheme.max' => 'L\'intitulé du thème ne doit pas dépasser 255 caractères.',
            'descTheme.string' => 'La description du thème doit être une chaîne de caractères.',
            'descTheme.max' => 'La description du thème ne doit pas dépasser 500 caractères.',
            'idAxeRech.required' => 'L\'axe de recherche est obligatoire.',
            'idAxeRech.array' => 'Veuillez sélectionner un axe de recherche valide.',
            'idAxeRech.min' => 'Veuillez sélectionner au moins un axe de recherche.',
            'idAxeRech.max' => 'Vous ne pouvez sélectionner qu\'un seul axe de recherche.',
            'idAxeRech.*.exists' => 'L\'axe de recherche sélectionné n\'existe pas.',
        ]);

        // Démarrage de la transaction
        DB::beginTransaction();

        try {
            // Création d'une instance de Theme
            $theme = new Theme();
            $theme->intituleTheme = $validated['intituleTheme'];
            $theme->descTheme = $validated['descTheme'] ?? null;

            // Assigner le premier axe de recherche sélectionné (car il y a un seul axe sélectionné)
            $theme->idAxeRech = $validated['idAxeRech'][0];  // On prend le premier élément du tableau

            $theme->save();  // Sauvegarde du thème

            // Validation de la transaction
            DB::commit();

            // Redirection avec un message de succès
            return redirect()->route('admin.listeTheme')->with('success', 'Thème ajouté avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();

            // Retourner un message d'erreur
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l’enregistrement.']);
        }
    }



    // Modifier un thème (pré-remplir les champs dans une autre vue)
    public function edit($id)
    {
        try {
            // Récupérer le thème avec sa relation axeRecherche
            $theme = Theme::with('axeRecherche')->findOrFail($id);

            // Récupérer tous les axes de recherche pour le select
            $axesRecherches = AxeRecherche::all();

            // Retourner la vue avec les données
            return view('lab.admin.modifier_theme', compact('theme', 'axesRecherches'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeTheme')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données du thème.');
        }
    }

    //rechercher un theme
    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Recherche dans la table 'themes'
        $themes = Theme::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('intituleTheme', 'like', '%' . $query . '%')
                             ->orWhereHas('axeRecherche', function ($axeQuery) use ($query) {
                                 $axeQuery->where('titreAxeRech', 'like', '%' . $query . '%');
                             });
            })
            ->with('axeRecherche') // Charger l'axe associé pour éviter les requêtes supplémentaires
            ->paginate(10); // Pagination des résultats

        $axesRecherches = AxeRecherche::all();

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_theme', compact('themes', 'query' , 'axesRecherches'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données
            $validated = $request->validate([
                'intituleTheme' => 'required|string|max:255',
                'descTheme' => 'nullable|string|max:500',
                'idAxeRech' => 'required|exists:axe_recherches,idAxeRech',
            ], [
                'intituleTheme.required' => 'L\'intitulé du thème est obligatoire.',
                'intituleTheme.max' => 'L\'intitulé du thème ne doit pas dépasser 255 caractères.',
                'descTheme.max' => 'La description du thème ne doit pas dépasser 500 caractères.',
                'idAxeRech.required' => 'L\'axe de recherche est obligatoire.',
                'idAxeRech.exists' => 'L\'axe de recherche sélectionné n\'existe pas.',
            ]);

            // Récupérer le thème
            $theme = Theme::findOrFail($id);

            // Mettre à jour les informations du thème
            $theme->update([
                'intituleTheme' => $validated['intituleTheme'],
                'descTheme' => $validated['descTheme'],
                'idAxeRech' => $validated['idAxeRech'],
            ]);

            // Commit de la transaction
            DB::commit();

            return redirect()->route('admin.listeTheme')
                ->with('success', 'Thème modifié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification du thème : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Trouver le thème à supprimer
            $theme = Theme::findOrFail($id);

            // Vérifier si des doctorants sont associés à ce thème
            if ($theme->doctorants()->exists()) {
                return redirect()->back()->with(
                    'error',
                    'Le thème ne peut pas être supprimé car il est associé à des doctorants. Veuillez d\'abord dissocier ou supprimer les doctorants liés.'
                );
            }

            // Démarrer une transaction pour garantir l'intégrité des données
            DB::beginTransaction();

            // Supprimer le thème
            $theme->delete();

            // Confirmer la transaction
            DB::commit();

            return redirect()->back()->with('success', 'Le thème a été supprimé avec succès.');
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la suppression du thème. Détails : ' . $e->getMessage()
            );
        }
    }

}
