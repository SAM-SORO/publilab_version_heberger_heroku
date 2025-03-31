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
            'nomGrade' => 'required|string|max:255|unique:grades,nomGrade',
            'sigleGrade' => 'required|string|max:50|unique:grades,sigleGrade',
        ], [
            'nomGrade.required' => 'Le nom du grade est obligatoire.',
            'nomGrade.unique' => 'Ce nom de grade existe déjà.',
            'sigleGrade.required' => 'Le sigle du grade est obligatoire.',
            'sigleGrade.unique' => 'Ce sigle de grade existe déjà.',
        ]);

        try {
            Grade::create($validated);
            return redirect()->route('admin.listeGrade')
                ->with('success', 'Grade ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    // Modifier un grade (optionnel)
    public function edit($id)
    {
        try {
            $grade = Grade::with(['chercheurs' => function($query) {
                $query->orderBy('chercheur_grade.dateGrade', 'desc');
            }])->findOrFail($id);

            return view('lab.admin.modifier_grade', compact('grade'));
        } catch (\Exception $e) {
            return redirect()->route('admin.listeGrade')
                ->with('error', 'Grade non trouvé.');
        }
    }


    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // Si la recherche est vide, retourner tous les grades
            if (empty($query)) {
                return redirect()->route('admin.listeGrade');
            }

            $grades = Grade::where(function($q) use ($query) {
                // Recherche sur le nom et le sigle du grade
                $q->where('nomGrade', 'like', '%' . $query . '%')
                  ->orWhere('sigleGrade', 'like', '%' . $query . '%');

                // Recherche sur les chercheurs associés
                $q->orWhereHas('chercheurs', function($subQuery) use ($query) {
                    $subQuery->where(DB::raw("CONCAT(nomCherch, ' ', prenomCherch)"), 'like', '%' . $query . '%')
                            ->orWhere(DB::raw("CONCAT(prenomCherch, ' ', nomCherch)"), 'like', '%' . $query . '%');
                });
            })
            ->orderByDesc('nomGrade')
            ->paginate(10)
            ->withQueryString();

            return view('lab.admin.liste_grade', compact('grades', 'query'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeGrade')
                ->with('error', 'Erreur lors de la recherche : ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $grade = Grade::findOrFail($id);

            $validated = $request->validate([
                'nomGrade' => 'required|string|max:255|unique:grades,nomGrade,' . $id . ',idGrade',
                'sigleGrade' => 'required|string|max:50|unique:grades,sigleGrade,' . $id . ',idGrade',
            ]);

            $grade->update($validated);

            return redirect()->route('admin.listeGrade')
                ->with('success', 'Grade modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $grade = Grade::findOrFail($id);

            // Détacher tous les chercheurs associés à ce grade
            $grade->chercheurs()->detach();

            // Supprimer le grade
            $grade->delete();

            DB::commit();
            return redirect()->route('admin.listeGrade')
                ->with('success', 'Grade supprimé avec succès. Les associations avec les chercheurs ont été supprimées.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.listeGrade')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

}
