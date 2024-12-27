<?php

namespace App\Http\Controllers;

use App\Models\Doctorant;
use App\Models\Theme;
use App\Models\Chercheur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorantController extends Controller
{
    public function index()
    {
        // Récupérer les doctorants triés par date de création (ou un autre champ pertinent) avec les relations 'theme' et 'encadrants'
        $doctorants = Doctorant::with(['theme', 'encadrants'])
            ->orderByDesc('created_at') // Tri par date de création (modifiez ce champ selon vos besoins)
            ->paginate(10); // Pagination avec 10 résultats par page

        // Récupérer tous les thèmes pour l'affichage dans la vue
        $themes = Theme::all();

        // Récupérer tous les chercheurs pour l'affichage dans la vue
        $chercheurs = Chercheur::all();

        // Retourner la vue avec les données
        return view('lab.admin.liste_doctorant', compact('doctorants', 'themes', 'chercheurs'));
    }



    public function create(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nomDoc' => 'required|string|max:255',
            'prenomDoc' => 'nullable|string|max:255', // Le prénom est facultatif
            'idTheme' => 'required',
            'idCherch' => 'required|array', // Vérifie que c'est un tableau
            'idCherch.*' => 'required', // Vérifie que chaque encadrant existe
            'dateDebut' => 'nullable|date', // La date de début n'est plus requise, mais doit être une date valide si présente
            'dateFin' => 'nullable|date', // La date de fin est facultative
        ], [
            'nomDoc.required' => 'Le nom du doctorant est obligatoire.',
            'nomDoc.max' => 'Le nom du doctorant ne doit pas dépasser 255 caractères.',
            'prenomDoc.max' => 'Le prénom du doctorant ne doit pas dépasser 255 caractères.',
            'idTheme.required' => 'Le thème de recherche est obligatoire.',
            'idCherch.required' => 'Au moins un encadrant est requis.',
            'idCherch.array' => 'Les encadrants doivent être envoyés sous forme de tableau.',
            'idCherch.*.required' => 'Chaque encadrant doit être sélectionné.',
            'dateDebut.date' => 'La date de début doit être une date valide.',
            'dateFin.date' => 'La date de fin doit être une date valide.',
        ]);

        // Utilisation d'une transaction pour garantir l'intégrité des données
        try {
            DB::beginTransaction();

            // Création du doctorant
            $doctorant = Doctorant::create([
                'nomDoc' => $validated['nomDoc'],
                'prenomDoc' => $validated['prenomDoc'] ?? null, // Gère le cas où le prénom n'est pas renseigné
                'idTheme' => $validated['idTheme'],
            ]);

            // Associer les encadrants avec les dates
            $encadrantsData = [];
            foreach ($validated['idCherch'] as $encadrantId) {
                // Si la date de début n'est pas fournie, elle sera définie sur null
                $encadrantsData[$encadrantId] = [
                    'dateDebut' => $validated['dateDebut'] ?: null, // Si pas de date, on l'assigne à null
                    'dateFin' => $validated['dateFin'] ?: null, // Si pas de date de fin, on l'assigne à null
                ];
            }

            // Associer les encadrants en une seule fois
            $doctorant->encadrants()->attach($encadrantsData);

            // Validation de la transaction
            DB::commit();

            return redirect()->route('admin.listeDoctorant')->with('success', 'Doctorant ajouté avec succès.');
        } catch (\Exception $e) {
            // Annulation de la transaction en cas d'erreur
            DB::rollBack();

            // Retourner l'erreur complète dans le message
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l’enregistrement. Détails : ' . $e->getMessage()]);
        }
    }


    // rechercher un doctorant
    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Recherche dans la table 'doctorants'
        $doctorants = Doctorant::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('nomDoc', 'like', '%' . $query . '%')
                             ->orWhere('prenomDoc', 'like', '%' . $query . '%')
                             ->orWhereHas('theme', function ($themeQuery) use ($query) {
                                 $themeQuery->where('descTheme', 'like', '%' . $query . '%');
                             });
            })
            ->with('theme') // Charger le thème associé pour éviter les requêtes supplémentaires
            ->paginate(10); // Pagination des résultats

        $themes = Theme::all();
        $chercheurs = Chercheur::all();

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_doctorant', compact('doctorants', 'query', 'themes' , 'chercheurs'));
    }


    public function edit($id)
    {
        try {
            // Récupérer le doctorant avec ses relations
            $doctorant = Doctorant::with(['theme', 'encadrants'])->findOrFail($id);

            // Récupérer tous les thèmes et chercheurs pour les listes déroulantes
            $themes = Theme::all();
            $chercheurs = Chercheur::all();

            // Retourner la vue avec les données
            return view('lab.admin.modifier_doctorant', compact('doctorant', 'themes', 'chercheurs'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeDoctorant')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données du doctorant.');
        }
    }

    public function update(Request $request, $id)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données
            $validated = $request->validate([
                'nomDoc' => 'required|string|max:255',
                'prenomDoc' => 'nullable|string|max:255',
                'idTheme' => 'required|exists:themes,idTheme',
                'idCherch' => 'required|array',
                'idCherch.*' => 'exists:chercheurs,idCherch',
                'dateDebut' => 'nullable|date', // Rend la dateDebut non requise, mais vérifie qu'elle est une date valide si fournie
                'dateFin' => 'nullable|date|after:dateDebut', // La dateFin reste facultative et doit être après la dateDebut si elle est renseignée
            ], [
                'nomDoc.required' => 'Le nom est obligatoire.',
                'nomDoc.max' => 'Le nom ne doit pas dépasser 255 caractères.',
                'prenomDoc.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
                'idTheme.required' => 'Le thème de recherche est obligatoire.',
                'idTheme.exists' => 'Le thème sélectionné n\'existe pas.',
                'idCherch.required' => 'Au moins un encadrant est requis.',
                'idCherch.*.exists' => 'Un des encadrants sélectionnés n\'existe pas.',
                'dateDebut.date' => 'La date de début doit être une date valide.',
                'dateFin.date' => 'La date de fin doit être une date valide.',
                'dateFin.after' => 'La date de fin doit être postérieure à la date de début.',
            ]);

            // Récupérer le doctorant
            $doctorant = Doctorant::findOrFail($id);

            // Mettre à jour les informations de base du doctorant
            $doctorant->update([
                'nomDoc' => $validated['nomDoc'],
                'prenomDoc' => $validated['prenomDoc'],
                'idTheme' => $validated['idTheme'],
            ]);

            // Détacher tous les encadrants existants
            $doctorant->encadrants()->detach();

            // Attacher les nouveaux encadrants avec les dates
            foreach ($validated['idCherch'] as $chercheurId) {
                $doctorant->encadrants()->attach($chercheurId, [
                    'dateDebut' => $validated['dateDebut'] ?? null, // Assure-toi que dateDebut est null si non fourni
                    'dateFin' => $validated['dateFin'] ?? null,    // La dateFin est aussi nullable
                ]);
            }

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeDoctorant')
                ->with('success', 'Doctorant modifié avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification du doctorant : ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // Récupérer le doctorant à supprimer
            $doctorant = Doctorant::findOrFail($id);

            // Dissocier les enregistrements dans les tables pivot
            $doctorant->encadrants()->detach();  // Supprimer les relations dans doctorant_chercheur
            $doctorant->articles()->detach();    // Supprimer les relations dans doctorant_article_chercheur

            // Supprimer le doctorant
            $doctorant->delete();

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeDoctorant')
                             ->with('success', 'Doctorant supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->route('admin.listeDoctorant')
                             ->with('error', 'Une erreur est survenue lors de la suppression du doctorant. Détails: ' . $e->getMessage());
        }
    }
}



// // Insérer dans la table pivot avec des champs explicites
// foreach ($encadrantsData as $data) {
//     $doctorant->encadrants()->attach($data['idCherch'], [
//         'dateDebut' => $data['dateDebut'],
//         'dateFin' => $data['dateFin'],
//     ]);
// }
