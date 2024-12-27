<?php

namespace App\Http\Controllers;

use App\Models\AxeRecherche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AxeRechercheController extends Controller
{
    public function index()
    {
        // Récupérer les axes de recherche triés par ordre décroissant (par exemple, selon la date de création)
        $axesRecherches = AxeRecherche::orderByDesc('created_at') // Trier par la colonne created_at en ordre décroissant
            ->paginate(10); // Pagination avec 10 résultats par page

        // Retourner la vue avec les axes de recherche
        return view('lab.admin.liste_axe_recherche', compact('axesRecherches'));
    }


    public function create(Request $request)
    {
        // Validation des données envoyées via le formulaire
        $request->validate([
            'titreAxeRech' => 'required|string|max:255',
            'descAxeRech' => 'nullable|string',
        ], [
            'titreAxeRech.required' => 'Le titre de l\'axe de recherche est requis.',
            'titreAxeRech.string' => 'Le titre doit être une chaîne de caractères.',
        ]);

        // Création d'un nouvel axe de recherche
        AxeRecherche::create([
            'titreAxeRech' => $request->input('titreAxeRech'),
            'descAxeRech' => $request->input('descAxeRech'),
        ]);

        return redirect()->route('admin.listeAxeRecherche')->with('success', 'Axe de recherche enregistré avec succès.');
    }


    public function edit($id)
    {
        try {
            // Récupérer l'axe de recherche
            $axeRecherche = AxeRecherche::findOrFail($id);

            // Retourner la vue avec les données
            return view('lab.admin.modifier_axe_recherche', compact('axeRecherche'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeAxeRecherche')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données de l\'axe de recherche.');
        }
    }

    public function update(Request $request, $id)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données
            $validated = $request->validate([
                'titreAxeRech' => 'required|string|max:255',
                'descAxeRech' => 'nullable|string',
            ], [
                'titreAxeRech.required' => 'Le titre de l\'axe de recherche est obligatoire.',
                'titreAxeRech.max' => 'Le titre de l\'axe de recherche ne doit pas dépasser 255 caractères.',
            ]);

            // Récupérer l'axe de recherche
            $axeRecherche = AxeRecherche::findOrFail($id);

            // Mettre à jour les informations
            $axeRecherche->update([
                'titreAxeRech' => $validated['titreAxeRech'],
                'descAxeRech' => $validated['descAxeRech'],
            ]);

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeAxeRecherche')
                ->with('success', 'Axe de recherche modifié avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'axe de recherche : ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        // Récupérer le texte de recherche
        $query = $request->input('query');

        // Effectuer la recherche dans les champs 'titreAxeRech' et 'descAxeRech'
        $axesRecherches = AxeRecherche::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('titreAxeRech', 'like', '%' . $query . '%')
                             ->orWhere('descAxeRech', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Ajouter la pagination pour les résultats

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_axe_recherche', compact('axesRecherches', 'query'));
    }


    public function delete($id)
    {
        try {
            // Trouver l'axe de recherche à supprimer
            $axe = AxeRecherche::findOrFail($id);

            // Vérifier si des thèmes sont associés à cet axe de recherche
            if ($axe->themes()->exists()) {
                return redirect()->back()->with(
                    'error',
                    'L\'axe de recherche ne peut pas être supprimé car il est associé à des thèmes. Veuillez d\'abord dissocier les thèmes associés.'
                );
            }

            // Démarrer une transaction pour garantir l'intégrité des données
            DB::beginTransaction();

            // Détacher les relations many-to-many avec les laboratoires
            $axe->laboratoires()->detach();

            // Supprimer l'axe de recherche
            $axe->delete();

            // Confirmer la transaction
            DB::commit();

            return redirect()->back()->with('success', 'L\'axe de recherche a été supprimé avec succès.');
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la suppression de l\'axe de recherche. Détails : ' . $e->getMessage()
            );
        }
    }


}
