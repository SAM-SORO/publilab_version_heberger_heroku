<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypePublication;
use Illuminate\Support\Facades\DB;

class TypePublicationController extends Controller
{
    // Liste des types de publication
    public function index()
    {
        $typesPublications = TypePublication::orderBy('created_at', 'desc')->paginate(10);
        return view('lab.admin.liste_type_publication', compact('typesPublications'));
    }

    // Création d'un type de publication
    public function create(Request $request)
    {
        $validated = $request->validate([
            'libeleTypePub' => 'required|string|max:255|unique:type_publications,libeleTypePub',
            'descTypePub' => 'nullable|string|max:500',
        ], [
            'libeleTypePub.required' => 'Le libellé du type de publication est requis.',
            'libeleTypePub.unique' => 'Ce libellé est déjà utilisé.',
        ]);

        try {
            TypePublication::create($validated);
            return redirect()->route('admin.listeTypePublications')
                ->with('success', 'Type de publication créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    // Recherche de types de publication
    public function search(Request $request)
    {
        $query = $request->input('query');

        $typesPublications = TypePublication::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('libeleTypePub', 'like', '%' . $query . '%')
                         ->orWhere('descTypePub', 'like', '%' . $query . '%');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('lab.admin.liste_type_publication', compact('typesPublications', 'query'));
    }

    // Édition d'un type de publication
    public function edit($id)
    {
        $typePublication = TypePublication::findOrFail($id);
        return view('lab.admin.modifier_type_publication', compact('typePublication'));
    }

    // Mise à jour d'un type de publication
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'libeleTypePub' => 'required|string|max:255|unique:type_publications,libeleTypePub,' . $id . ',idTypePub',
            'descTypePub' => 'nullable|string|max:500',
        ], [
            'libeleTypePub.required' => 'Le libellé du type de publication est requis.',
            'libeleTypePub.unique' => 'Ce libellé est déjà utilisé.',
        ]);

        try {
            $typePublication = TypePublication::findOrFail($id);
            $typePublication->update($validated);
            return redirect()->route('admin.listeTypePublications')
                ->with('success', 'Type de publication modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    // Suppression d'un type de publication
    public function delete($id)
    {
        try {
            $typePublication = TypePublication::findOrFail($id);
            $typePublication->delete();
            return redirect()->route('admin.listeTypePublications')
                ->with('success', 'Type de publication supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.listeTypePublications')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
