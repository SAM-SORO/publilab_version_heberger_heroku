<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeArticle;
use Illuminate\Support\Facades\DB;

class TypeArticleController extends Controller
{
    // Liste des types d'articles
    public function index()
    {
        $typesArticles = TypeArticle::orderBy('created_at', 'desc')->paginate(10);
        return view('lab.admin.liste_type_article', compact('typesArticles'));
    }

    // Création d'un type d'article
    public function create(Request $request)
    {
        $validated = $request->validate([
            'nomTypeArticle' => 'required|string|max:255|unique:type_articles,nomTypeArticle',
            'descTypeArticle' => 'nullable|string|max:500',
        ], [
            'nomTypeArticle.required' => 'Le nom du type d\'article est requis.',
            'nomTypeArticle.unique' => 'Ce nom de type d\'article est déjà utilisé.',
        ]);

        try {
            TypeArticle::create($validated);
            return redirect()->route('admin.listeTypeArticle')
                ->with('success', 'Type d\'article créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    // Recherche de types d'articles
    public function search(Request $request)
    {
        $query = $request->input('query');

        $typesArticles = TypeArticle::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('nomTypeArticle', 'like', '%' . $query . '%')
                         ->orWhere('descTypeArticle', 'like', '%' . $query . '%');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('lab.admin.liste_type_article', compact('typesArticles', 'query'));
    }

    // Édition d'un type d'article
    public function edit($id)
    {
        $typeArticle = TypeArticle::findOrFail($id);
        return view('lab.admin.modifier_type_article', compact('typeArticle'));
    }

    // Mise à jour d'un type d'article
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nomTypeArticle' => 'required|string|max:255|unique:type_articles,nomTypeArticle,' . $id . ',idTypeArticle',
            'descTypeArticle' => 'nullable|string|max:500',
        ], [
            'nomTypeArticle.required' => 'Le nom du type d\'article est requis.',
            'nomTypeArticle.unique' => 'Ce nom de type d\'article est déjà utilisé.',
        ]);

        try {
            $typeArticle = TypeArticle::findOrFail($id);
            $typeArticle->update($validated);
            return redirect()->route('admin.listeTypeArticle')
                ->with('success', 'Type d\'article modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    // Suppression d'un type d'article
    public function delete($id)
    {
        try {
            $typeArticle = TypeArticle::findOrFail($id);
            $typeArticle->delete();
            return redirect()->route('admin.listeTypeArticle')
                ->with('success', 'Type d\'article supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.listeTypeArticle')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
