<?php

namespace App\Http\Controllers;

use App\Models\BdIndexation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BdIndexationController extends Controller
{
    public function index()
    {
        // Récupérer les bases d'indexation triées par date de création en ordre décroissant
        $bdIndexations = BdIndexation::orderByDesc('created_at')  // Tri par date de création en ordre décroissant
            ->paginate(10); // Pagination avec 10 résultats par page

        // Retourner la vue avec les bases d'indexation
        return view('lab.admin.liste_bdIndexation', compact('bdIndexations'));
    }


     // Affiche le formulaire de création d'une base d'indexation
     public function create(Request $request)
     {
        $request->validate([
            'nomBDInd' => 'required|string|max:255',
        ]);

        BdIndexation::create([
            'nomBDInd' => $request->nomBDInd,
        ]);

        return redirect()->route('admin.listeBaseIndexation')->with('success', 'Base d\'indexation ajoutée avec succès');
     }

     // Affiche le formulaire d'édition d'une base d'indexation
     public function edit($id)
     {
        $bdIndexation = BdIndexation::findOrFail($id);
        return view('lab.admin.modifier_bdIndexation', compact('bdIndexation'));
     }

     // Met à jour une base d'indexation
     public function update(Request $request, $id)
     {
        $request->validate([
            'nomBDInd' => 'required|string|max:255',
        ]);

        $bdIndexation = BdIndexation::findOrFail($id);
        $bdIndexation->update([
            'nomBDInd' => $request->nomBDInd,
        ]);

        return redirect()->route('admin.listeBaseIndexation')->with('success', 'Base d\'indexation mise à jour avec succès');
    }


    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Effectuer la recherche dans la base de données
        $bdIndexations = BdIndexation::query()
            ->when($query, function ($queryBuilder) use ($query) {
                // Chercher dans le champ 'nomBDInd'
                $queryBuilder->where('nomBDInd', 'like', '%' . $query . '%');
            })
            ->paginate(10);  // Pagination des résultats

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_bdIndexation', compact('bdIndexations', 'query'));
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // Récupérer la base d'indexation à supprimer
            $bdIndexation = BdIndexation::findOrFail($id);

            // Dissocier les revues associées
            $bdIndexation->revues()->detach();

            // Supprimer la base d'indexation
            $bdIndexation->delete();

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeBaseIndexation')
                             ->with('success', 'Base d\'indexation supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->route('admin.listeBaseIndexation')
                             ->with('error', 'Une erreur est survenue lors de la suppression de la base d\'indexation. Détails: ' . $e->getMessage());
        }
    }

}
