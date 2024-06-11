<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Chercheur;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class chercheurController extends Controller
{

    public function listeArticles()
    {
        $articles = Auth::user()->articles()->with('documents')->paginate(10);
        return view('lab.chercheur.index', compact('articles'));
    }

    public function modifierArticle(Request $request, Article $article){
        return view('lab.chercheur.modifier_article', compact('article'));

    }

    public function telecharger(Document $document)
    {
        return Storage::download('public/' . $document->lien);
    }


    public function enregistrerModificationArticle(Request $request, Article $article)
    {
        // Valider les données du formulaire sans limiter la taille des fichiers
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'document' => 'nullable|file|mimes:pdf', // Suppression de 'max:5120'
            'image_document' => 'nullable|image|mimes:jpg,png,jpeg', // Suppression de 'max:2048'
        ], [
            'titre.required' => 'Le titre de l\'article est requis.',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'description.required' => 'La description de l\'article est requise.',
            'document.mimes' => 'Le document doit être au format PDF.',
            'image_document.image' => 'L\'image doit être au format JPG, PNG ou JPEG.',
            'image_document.mimes' => 'L\'image doit être au format JPG, PNG ou JPEG.',
        ]);

        // Mettre à jour les champs de l'article
        $article->titre = $request->input('titre');
        $article->description = $request->input('description');
        $article->save();

        // Vérifier si un document existe déjà, sinon en créer un nouveau
        $document = $article->document ?: new Document();
        $document->num_art = $article->id;

        // Mettre à jour les fichiers s'ils sont fournis
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
            $document->lien = $documentPath;
        }

        if ($request->hasFile('image_document')) {
            $imageDocumentPath = $request->file('image_document')->store('images', 'public');
            $document->image = $imageDocumentPath;
        }

        // Sauvegarder les modifications du document seulement s'il a été créé ou modifié
        if ($document->isDirty() || !$document->exists) {
            $document->save();
        }

        // Rediriger avec un message de succès
        return redirect()->route('chercheur.espace')->with('success', 'Article modifié avec succès!');
    }



    public function supprimerArticle($articleId)
    {
        // Supprime l'article spécifié
        $article = Article::findOrFail($articleId);
        $article->delete();

        return redirect()->route('chercheur.espace')->with('success', 'Article supprimé avec succès');
    }

    public function publierArticle(){
        return view('lab.chercheur.publier_article');

    }


    public function enregistrerPublication(Request $request)
    {
        // Valider les données du formulaire sans limiter la taille des fichiers
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'document' => 'required|file|mimes:pdf', // Suppression de 'max:5120'
            'image_document' => 'required|image|mimes:jpg,png,jpeg', // Suppression de 'max:2048'
        ], [
            'titre.required' => 'Le titre de l\'article est requis.',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'description.required' => 'La description de l\'article est requise.',
            'document.required' => 'Le document PDF est requis.',
            'document.mimes' => 'Le document doit être au format PDF.',
            'image_document.required' => 'L\'image du document est requise.',
            'image_document.image' => 'L\'image doit être au format JPG, PNG ou JPEG.',
            'image_document.mimes' => 'L\'image doit être au format JPG, PNG ou JPEG.',
        ]);


        // Enregistrer les fichiers
        $documentPath = $request->file('document')->store('documents', 'public');
        $imageDocumentPath = $request->file('image_document')->store('images', 'public');

        // Créer un nouvel article
        $article = new Article();
        $article->titre = $request->input('titre');
        $article->description = $request->input('description');
        $article->id_ch = auth()->id(); // Associez l'article au chercheur connecté
        $article->save();

        // Enregistrer les informations sur le document
        $document = new Document();
        $document->num_art = $article->id;
        $document->format = 'pdf';
        $document->lien = $documentPath;
        $document->image = $imageDocumentPath;
        $document->save();

        // Rediriger avec un message de succès
        return redirect()->route('chercheur.publierArticle')->with('success', 'Article publié avec succès!');
    }






    public function modifierProfil(Request $request, $id) {
        // Messages de validation personnalisés
        $messages = [
            'required' => 'Le champ :attribute est requis.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'L\'adresse email est déjà utilisée par un autre compte.',
            'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
            'min' => 'Le champ :attribute doit avoir au moins :min caractères.',
            'numeric' => 'Le champ :attribute doit être composé de chiffres.',
            'digits' => 'Le champ :attribute doit avoir :digits chiffres exactement.',
        ];

        /// Récupérer l'utilisateur connecté
        $chercheur = Chercheur::findOrFail($id);

        // Créer un tableau de règles de validation
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:chercheurs,email,'.$chercheur->id,
        ];

        // Ajouter des règles de validation si un nouveau mot de passe est fourni
        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|string';
            $rules['new_password'] = 'required|string|min:8';
            $rules['confirm_password'] = 'required|string|same:new_password';
        }

        // Valider les données
        $request->validate($rules, $messages);

        // Vérifiez si un nouveau mot de passe a été fourni
        if ($request->filled('new_password')) {
            // Vérifiez le mot de passe actuel
            if (!Hash::check($request->input('current_password'), $chercheur->password)) {
                return redirect()->back()->with('error', 'Le mot de passe actuel saisi est incorrect.');
            }

            // Hasher et enregistrer le nouveau mot de passe
            $chercheur->password = Hash::make($request->input('new_password'));
        }

        // Enregistrez les modifications
        $chercheur->save();

        // Redirigez l'utilisateur vers une page de confirmation ou une autre destination
        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }



    public function profil(){
        return view('lab.chercheur.profil');
    }


}
