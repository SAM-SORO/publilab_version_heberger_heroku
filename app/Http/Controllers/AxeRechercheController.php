<?php

namespace App\Http\Controllers;

use App\Models\AxeRecherche;
use App\Models\Laboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AxeRechercheController extends Controller
{
    public function index()
    {
        // Récupérer les axes de recherche avec leurs relations
        $axeRecherches = AxeRecherche::with([
            'themes',
            'laboratoires.directeur'
        ])
        ->orderByDesc('created_at')
        ->paginate(10);

        // Récupérer tous les laboratoires pour le formulaire d'ajout
        $laboratoires = Laboratoire::all();

        return view('lab.admin.liste_axe_recherche',
            compact('axeRecherches', 'laboratoires'));
    }

    public function create(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'titreAxeRech' => 'required|string|max:255|unique:axe_recherches,titreAxeRech',
                'descAxeRech' => 'nullable|string|max:1000',
                'laboratoires' => 'nullable|array',
                'laboratoires.*' => 'exists:laboratoires,idLabo'
            ]);

            // Création de l'axe de recherche
            $axeRecherche = AxeRecherche::create([
                'titreAxeRech' => $validated['titreAxeRech'],
                'descAxeRech' => $validated['descAxeRech']
            ]);

            // Associer les laboratoires si présents
            if (isset($validated['laboratoires'])) {
                $axeRecherche->laboratoires()->attach($validated['laboratoires']);
            }

            return redirect()->route('admin.listeAxeRecherche')
                ->with('success', 'Axe de recherche créé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Récupérer l'axe de recherche avec ses relations
            $axeRecherche = AxeRecherche::with(['themes'])
                ->findOrFail($id);

            return view('lab.admin.modifier_axe_recherche', compact('axeRecherche'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeAxeRecherche')
                ->with('error', 'Impossible de trouver l\'axe de recherche demandé : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'titreAxeRech' => 'required|string|max:255',
            'descAxeRech' => 'nullable|string'
        ], [
            'titreAxeRech.required' => 'Le titre est obligatoire',
            'titreAxeRech.max' => 'Le titre ne doit pas dépasser 255 caractères'
        ]);

        try {
            DB::beginTransaction();

            // Récupérer l'axe de recherche
            $axeRecherche = AxeRecherche::findOrFail($id);

            // Mise à jour des données
            $axeRecherche->update([
                'titreAxeRech' => $validated['titreAxeRech'],
                'descAxeRech' => $validated['descAxeRech']
            ]);

            DB::commit();
            return redirect()->route('admin.listeAxeRecherche')
                ->with('success', 'Axe de recherche modifié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // Si la recherche est vide, retourner à la liste
            if (empty($query)) {
                return redirect()->route('admin.listeAxeRecherche');
            }

            $axeRecherches = AxeRecherche::with(['themes'])
                ->where(function($q) use ($query) {
                    $q->where('titreAxeRech', 'like', '%' . $query . '%')
                      ->orWhere('descAxeRech', 'like', '%' . $query . '%')
                      // Recherche dans les thèmes associés
                      ->orWhereHas('themes', function($subQ) use ($query) {
                          $subQ->where('intituleTheme', 'like', '%' . $query . '%');
                      });
                })
                ->orderBy('titreAxeRech', 'asc')
                ->paginate(10)
                ->withQueryString();

            // Récupérer les laboratoires pour le formulaire d'ajout
            $laboratoires = Laboratoire::all();

            return view('lab.admin.liste_axe_recherche', compact('axeRecherches', 'laboratoires', 'query'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeAxeRecherche')
                ->with('error', 'Erreur lors de la recherche : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $axe = AxeRecherche::findOrFail($id);

            if ($axe->hasThemes()) {
                throw new \Exception('Impossible de supprimer cet axe car il possède des thèmes associés.');
            }

            $axe->laboratoires()->detach();
            $axe->delete();

            DB::commit();
            return redirect()->route('admin.listeAxeRecherche')
                ->with('success', 'Axe de recherche supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Impossible de supprimer l\'axe : ' . $e->getMessage());
        }
    }
}
