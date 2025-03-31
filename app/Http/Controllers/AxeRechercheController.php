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
            'laboratoire'  // Changé de laboratoires à laboratoire
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
                'idLabo' => 'nullable|exists:laboratoires,idLabo'  // Modification ici
            ]);

            // Création de l'axe de recherche
            $axeRecherche = AxeRecherche::create([
                'titreAxeRech' => $validated['titreAxeRech'],
                'descAxeRech' => $validated['descAxeRech'],
                'idLabo' => $validated['idLabo'] ?? null  // Ajout de idLabo
            ]);

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
            $axeRecherche = AxeRecherche::with(['themes', 'laboratoire'])
                ->findOrFail($id);

            // Récupérer tous les laboratoires pour le formulaire
            $laboratoires = Laboratoire::all();

            return view('lab.admin.modifier_axe_recherche',
                compact('axeRecherche', 'laboratoires'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeAxeRecherche')
                ->with('error', 'Impossible de trouver l\'axe de recherche : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'titreAxeRech' => 'required|string|max:255',
            'descAxeRech' => 'nullable|string',
            'idLabo' => 'nullable|exists:laboratoires,idLabo'  // Ajout de la validation du laboratoire
        ]);

        try {
            DB::beginTransaction();

            // Récupérer l'axe de recherche
            $axeRecherche = AxeRecherche::findOrFail($id);

            // Mise à jour des données
            $axeRecherche->update([
                'titreAxeRech' => $validated['titreAxeRech'],
                'descAxeRech' => $validated['descAxeRech'],
                'idLabo' => $validated['idLabo']  // Mise à jour du laboratoire
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

            if (empty($query)) {
                return redirect()->route('admin.listeAxeRecherche');
            }

            $axeRecherches = AxeRecherche::with(['themes', 'laboratoire'])  // Changé de laboratoires à laboratoire
                ->where(function($q) use ($query) {
                    $q->where('titreAxeRech', 'like', '%' . $query . '%')
                      ->orWhere('descAxeRech', 'like', '%' . $query . '%')
                      ->orWhereHas('laboratoire', function($subQ) use ($query) {  // Recherche dans le laboratoire
                          $subQ->where('sigleLabo', 'like', '%' . $query . '%')
                                ->orWhere('nomLabo', 'like', '%' . $query . '%');
                      })
                      ->orWhereHas('themes', function($subQ) use ($query) {
                          $subQ->where('intituleTheme', 'like', '%' . $query . '%');
                      });
                })
                ->orderBy('titreAxeRech', 'asc')
                ->paginate(10)
                ->withQueryString();

            $laboratoires = Laboratoire::all();

            return view('lab.admin.liste_axe_recherche',
                compact('axeRecherches', 'laboratoires', 'query'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeAxeRecherche')
                ->with('error', 'Erreur lors de la recherche : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $axe = AxeRecherche::findOrFail($id);

            if ($axe->hasThemes()) {
                throw new \Exception('Impossible de supprimer cet axe car il possède des thèmes associés.');
            }

            $axe->delete();

            return redirect()->route('admin.listeAxeRecherche')
                ->with('success', 'Axe de recherche supprimé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer l\'axe : ' . $e->getMessage());
        }
    }
}
