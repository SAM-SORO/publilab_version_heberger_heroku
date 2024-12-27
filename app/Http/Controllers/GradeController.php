<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    // Afficher la liste des grades
    public function index()
    {
        // Récupérer les grades triés par ordre décroissant (par date de création ou autre critère pertinent)
        $grades = Grade::orderByDesc('created_at')->paginate(10); // Paginer les résultats

        // Retourner la vue avec les grades
        return view('lab.admin.liste_grade', compact('grades'));
    }


    // Enregistrer un grade
    public function create(Request $request)
    {
        $validated = $request->validate([
            'nomGrade' => 'required|string|max:255',
            'sigleGrade' => 'nullable|string|max:255',
        ]);

        Grade::create($validated);

        return redirect()->route('admin.listeGrade')->with('success', 'Grade ajouté avec succès.');
    }

    // Modifier un grade (optionnel)
    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        return view('lab.admin.modifier_grade', compact('grade'));
    }


    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Effectuer la recherche dans la table 'grades'
        $grades = Grade::query()
            ->when($query, function ($queryBuilder) use ($query) {
                // Recherche sur les champs 'nomGrade' et 'sigleGrade'
                $queryBuilder->where('nomGrade', 'like', '%' . $query . '%')
                             ->orWhere('sigleGrade', 'like', '%' . $query . '%');
            })
            ->paginate(10);  // Pagination des résultats

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_grade', compact('grades', 'query'));
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
                'nomGrade' => 'required|string|max:255',
                'sigleGrade' => 'nullable|string|max:255',
            ], [
                'nomGrade.required' => 'Le nom du grade est obligatoire.',
                'nomGrade.max' => 'Le nom du grade ne doit pas dépasser 255 caractères.',
                'sigleGrade.max' => 'Le sigle du grade ne doit pas dépasser 255 caractères.',
            ]);

            // Récupérer le grade
            $grade = Grade::findOrFail($id);

            // Mettre à jour les informations du grade
            $grade->update([
                'nomGrade' => $validated['nomGrade'],
                'sigleGrade' => $validated['sigleGrade'],
            ]);

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeGrade')
                ->with('success', 'Grade modifié avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification du grade : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // Récupérer le grade à supprimer
            $grade = Grade::findOrFail($id);

            // Dissocier les chercheurs associés à ce grade
            $grade->chercheurs()->detach();

            // Supprimer le grade
            $grade->delete();

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeGrade')
                            ->with('success', 'Grade supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->route('admin.listeGrade')
                            ->with('error', 'Une erreur est survenue lors de la suppression du grade. Détails: ' . $e->getMessage());
        }
    }

}
