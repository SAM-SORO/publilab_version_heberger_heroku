<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Revue;
use App\Models\BdIndexation;
use Illuminate\Support\Facades\DB;

class RevueController extends Controller
{


    // Affiche la liste des revues
    public function index()
    {
        // Récupérer les revues triées par date de création en ordre décroissant
        $revues = Revue::orderByDesc('created_at')  // Tri par date de création en ordre décroissant
            ->paginate(10); // Pagination avec 10 résultats par page

        // Récupérer toutes les bases d'indexation disponibles
        $bdIndexations = BdIndexation::all();

        // Retourner la vue avec les revues et les bases d'indexation
        return view('lab.admin.liste_revue', compact('revues', 'bdIndexations'));
    }



    public function create(Request $request)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données
            $request->validate([
                'ISSN' => 'nullable|string|max:255',
                'nomRevue' => 'required|string|max:255',
                'descRevue' => 'nullable|string',
                'typeRevue' => 'nullable|string|max:255',
                'bdIndexation' => 'nullable|exists:bd_indexations,idBDIndex', // Peut être null pour une sélection ultérieure
                'dateDebut' => 'nullable|date',
                'dateFin' => 'nullable|date|after_or_equal:dateDebut',
            ], [
                // Messages personnalisés
                'ISSN.max' => 'Le numéro ISSN ne peut pas dépasser 255 caractères.',
                'nomRevue.required' => 'Le nom de la revue est obligatoire.',
                'nomRevue.max' => 'Le nom de la revue ne doit pas dépasser 255 caractères.',
                'typeRevue.max' => 'Le type de revue ne doit pas dépasser 255 caractères.',
                'bdIndexation.exists' => 'La base d\'indexation sélectionnée est invalide.',
                'dateDebut.date' => 'La date de début doit être une date valide.',
                'dateFin.date' => 'La date de fin doit être une date valide.',
                'dateFin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
            ]);

            // Enregistrement de la revue
            $revue = Revue::create([
                'ISSN' => $request->ISSN,
                'nomRevue' => $request->nomRevue,
                'descRevue' => $request->descRevue,
                'typeRevue' => $request->typeRevue,
            ]);

            // Si une base d'indexation est sélectionnée, l'attacher
            if ($request->bdIndexation) {
                $revue->bdIndexations()->attach(
                    $request->bdIndexation,
                    [
                        'dateDebut' => $request->dateDebut,
                        'dateFin' => $request->dateFin,
                    ]
                );
            }

            // Commit de la transaction
            DB::commit();

            // Redirection après enregistrement
            return redirect()->route('admin.listeRevue')->with('success', 'Revue ajoutée avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Redirection avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout de la revue : ' . $e->getMessage());
        }
    }



    // Méthode pour modifier une revue (optionnel, si nécessaire)
    public function edit($id)
    {
        $revue = Revue::findOrFail($id);
        $bdIndexations = BdIndexation::all();
        return view('lab.admin.modifier_revue', compact('revue', 'bdIndexations'));
    }



    public function update(Request $request, $id)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données
            $validated = $request->validate([
                'ISSN' => 'nullable|string|max:255', // ISSN devient nullable
                'nomRevue' => 'required|string|max:255',
                'descRevue' => 'nullable|string',
                'typeRevue' => 'nullable|string|max:255', // Désormais non obligatoire
                'bdIndexation' => 'required|exists:bd_indexations,idBDIndex',
                'dateDebut' => 'nullable|date', // dateDebut devient nullable
                'dateFin' => 'nullable|date|after_or_equal:dateDebut', // dateFin devient nullable
            ], [
                'ISSN.max' => 'Le numéro ISSN ne doit pas dépasser 255 caractères.',
                'nomRevue.required' => 'Le nom de la revue est obligatoire.',
                'nomRevue.max' => 'Le nom de la revue ne doit pas dépasser 255 caractères.',
                'typeRevue.max' => 'Le type de revue ne doit pas dépasser 255 caractères.',
                'bdIndexation.required' => 'La base d\'indexation est obligatoire.',
                'bdIndexation.exists' => 'La base d\'indexation sélectionnée n\'existe pas.',
                'dateDebut.date' => 'La date de début doit être une date valide.',
                'dateFin.date' => 'La date de fin doit être une date valide.',
                'dateFin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
            ]);

            // Récupérer la revue
            $revue = Revue::findOrFail($id);

            // Mettre à jour les informations de base de la revue
            $revue->update([
                'ISSN' => $validated['ISSN'] ?? null, // Accepte les valeurs nulles
                'nomRevue' => $validated['nomRevue'],
                'descRevue' => $validated['descRevue'],
                'typeRevue' => $validated['typeRevue'] ?? null, // Accepte les valeurs nulles
            ]);

            // Détacher d'abord toutes les relations existantes
            $revue->bdIndexations()->detach();

            // Attacher la nouvelle relation avec les dates, en vérifiant les valeurs nulles
            $revue->bdIndexations()->attach($validated['bdIndexation'], [
                'dateDebut' => $validated['dateDebut'], // Peut être null
                'dateFin' => $validated['dateFin'],     // Peut être null
            ]);

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeRevue')
                ->with('success', 'Revue modifiée avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification de la revue : ' . $e->getMessage());
        }
    }




    public function search(Request $request)
    {
        // Récupérer la requête utilisateur
        $query = $request->input('query');

        // Construire la requête de recherche
        $revues = Revue::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('nomRevue', 'like', '%' . $query . '%')
                             ->orWhere('ISSN', 'like', '%' . $query . '%')
                             ->orWhere('typeRevue', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Pagination des résultats

        $bdIndexations = BdIndexation::all(); // Récupérer toutes les bases d'indexation disponibles

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_revue', compact('revues', 'query', 'bdIndexations'));
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // Récupérer la revue à supprimer
            $revue = Revue::findOrFail($id);

            // Vérifier si la revue est référencée par au moins un article
            if ($revue->articles()->exists()) {
                return redirect()->route('admin.listeRevue')
                                ->with('error', 'Impossible de supprimer la revue car elle est référencée par un ou plusieurs articles.');
            }

            // Dissocier les relations avec les bases d'indexation
            $revue->bdIndexations()->detach();

            // Supprimer la revue
            $revue->delete();

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeRevue')
                            ->with('success', 'Revue supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->route('admin.listeRevue')
                            ->with('error', 'Une erreur est survenue lors de la suppression de la revue. Détails: ' . $e->getMessage());
        }
    }


}
