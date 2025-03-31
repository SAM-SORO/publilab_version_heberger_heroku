<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Doctorant;
use App\Models\AxeRecherche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    // Affichage des thèmes
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $themesQuery = Theme::with('axeRecherche')
            ->when($filter, function ($query) use ($filter) {
                if ($filter === 'attributed') {
                    return $query->where('etatAttribution', true);
                } elseif ($filter === 'not-attributed') {
                    return $query->where('etatAttribution', false);
                }
            })
            ->orderByDesc('created_at');

        $themes = $themesQuery->paginate(10);
        $axesRecherches = AxeRecherche::all();

        return view('lab.admin.liste_theme', compact('themes', 'axesRecherches'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'titreTheme' => 'required|string|max:255',
            'descTheme' => 'nullable|string',
            'idAxeRech' => 'required|exists:axe_recherches,idAxeRech',
            'idDoctorant' => 'nullable|exists:doctorants,idDoctorant'
        ]);

        try {
            // Opération simple, pas besoin de transaction
            Theme::create($validated);

            return redirect()->route('admin.listeTheme')
                ->with('success', 'Thème créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
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
        $query = $request->input('query');

        $themes = Theme::with(['axeRecherche', 'doctorants'])
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('intituleTheme', 'like', '%' . $query . '%')
                    ->orWhere('descTheme', 'like', '%' . $query . '%');
            })
            ->orderBy('intituleTheme', 'asc')
            ->paginate(10);

        // Récupérer les axes de recherche pour le formulaire d'ajout
        $axesRecherches = AxeRecherche::all();

        return view('lab.admin.liste_theme', compact('themes', 'axesRecherches', 'query'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'intituleTheme' => 'required|string|max:255',
            'descTheme' => 'nullable|string',
            'idAxeRech' => 'required|exists:axe_recherches,idAxeRech',
            'etatAttribution' => 'nullable|boolean'
        ]);

        try {
            DB::beginTransaction();

            $theme = Theme::findOrFail($id);

            // Vérifier si l'état d'attribution passe de true à false
            if ($theme->etatAttribution && !isset($validated['etatAttribution'])) {
                // Mettre à jour les doctorants associés à ce thème
                Doctorant::where('idTheme', $theme->idTheme)
                    ->update(['idTheme' => null]);
            }

            // Préparer les données pour la mise à jour
            $dataToUpdate = [
                'intituleTheme' => $validated['intituleTheme'],
                'descTheme' => $validated['descTheme'],
                'idAxeRech' => $validated['idAxeRech'],
                'etatAttribution' => isset($validated['etatAttribution']) ? true : false
            ];

            $theme->update($dataToUpdate);

            DB::commit();
            return redirect()->route('admin.listeTheme')
                ->with('success', 'Thème modifié avec succès. ' .
                    (!isset($validated['etatAttribution']) && $theme->etatAttribution ?
                    'Les doctorants associés ont été détachés.' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Trouver le thème à supprimer
            $theme = Theme::findOrFail($id);

            // Vérifier s'il est attribué à des doctorants
            if ($theme->doctorants()->exists()) {
                return redirect()->back()->with(
                    'error',
                    'Suppression impossible : ce thème est attribué à des doctorants. Veuillez d\'abord réassigner ces doctorants.'
                );
            }

            // Démarrer une transaction
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
                'Une erreur est survenue lors de la suppression du thème : ' . $e->getMessage()
            );
        }
    }
}
