<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Chercheur;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        // Code pour afficher l'espace administrateur
        return view('lab.admin.index');
    }

    public function listeChercheur()
    {
        // Récupérer et afficher la liste des chercheurs
        $chercheurs = Chercheur::all();
        return view('lab.admin.liste_chercheur', compact('chercheurs'));
    }

    public function enregistrerChercheur()
    {
        // Afficher le formulaire d'enregistrement d'un chercheur
        return view('lab.admin.enregistrer_chercheur');
    }

    public function modifierChercheur($chercheurId)
    {
        // Afficher le formulaire de modification d'un chercheur
        $chercheur = Chercheur::findOrFail($chercheurId);
        return view('lab.admin.modifier_chercheur', compact('chercheur'));
    }

    public function profilAdmin(){
        return view('lab.admin.profil');
    }

    public function supprimerChercheur($chercheurId)
    {
        // Supprimer le chercheur spécifié
        $chercheur = Chercheur::findOrFail($chercheurId);
        $chercheur->delete();

        return redirect()->route('lab.admin.chercheur')->with('success', 'Chercheur supprimé avec succès');
    }

    public function listeArticlePublier()
    {
        // Récupérer et afficher la liste des articles à publier
        $articles = Article::where('status', 'à publier')->get();
        return view('lab.admin.liste_article_publier', compact('articles'));
    }

    public function publierArticle($articleId)
    {
        // Publier l'article spécifié
        $article = Article::findOrFail($articleId);
        $article->status = 'publié';
        $article->save();

        return redirect()->route('lab.admin.liste_article_publier')->with('success', 'Article publié avec succès');
    }

    public function supprimerArticle($articleId)
    {
        // Supprimer l'article spécifié
        $article = Article::findOrFail($articleId);
        $article->delete();

        return redirect()->route('lab.admin.liste_article_publier')->with('success', 'Article supprimé avec succès');
    }


}
