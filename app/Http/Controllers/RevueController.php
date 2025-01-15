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
                'bdIndexation' => 'nullable|array', // Vérifie que c'est un tableau
                'bdIndexation.*' => 'exists:bd_indexations,idBDIndex', // Chaque élément doit exister dans la table bd_indexations
                'dateDebut' => 'nullable|array', // Tableau des dates de début
                'dateDebut.*' => 'nullable|date', // Chaque date doit être valide
                'dateFin' => 'nullable|array', // Tableau des dates de fin
                'dateFin.*' => 'nullable|date|after_or_equal:dateDebut.*', // Chaque date de fin doit être valide et >= dateDebut
            ], [
                // Messages personnalisés
                'ISSN.max' => 'Le numéro ISSN ne peut pas dépasser 255 caractères.',
                'nomRevue.required' => 'Le nom de la revue est obligatoire.',
                'nomRevue.max' => 'Le nom de la revue ne doit pas dépasser 255 caractères.',
                'typeRevue.max' => 'Le type de revue ne doit pas dépasser 255 caractères.',
                'bdIndexation.*.exists' => 'L\'une des bases d\'indexation sélectionnées est invalide.',
                'dateDebut.*.date' => 'L\'une des dates de début n\'est pas valide.',
                'dateFin.*.date' => 'L\'une des dates de fin n\'est pas valide.',
                'dateFin.*.after_or_equal' => 'Chaque date de fin doit être égale ou postérieure à la date de début correspondante.',
            ]);





            // Enregistrement de la revue
            $revue = Revue::create([
                'ISSN' => $request->ISSN,
                'nomRevue' => $request->nomRevue,
                'descRevue' => $request->descRevue,
                'typeRevue' => $request->typeRevue,
            ]);

            // Attachement des bases d'indexation avec leurs dates correspondantes
            if ($request->bdIndexation) {
                foreach ($request->bdIndexation as $index => $bdIndexationId) {


                    // Vérifier et définir les valeurs des dates
                    $dateDebut = !empty($request->dateDebut[$index]) ? $request->dateDebut[$index] : null;
                    $dateFin = !empty($request->dateFin[$index]) ? $request->dateFin[$index] : null;

                    // Attacher à la revue uniquement si les dates sont valides ou nulles
                    $revue->bdIndexations()->attach($bdIndexationId, [
                        'dateDebut' => $dateDebut,
                        'dateFin' => $dateFin,
                    ]);
                }
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
                ->with('error', 'Une erreur est survenue lors de l\'ajout de la revue.');
        }
    }


    // Méthode pour modifier une revue (optionnel, si nécessaire)
    public function edit($id)
    {
        $revue = Revue::findOrFail($id);
        $bdIndexations = BdIndexation::all();
        return view('lab.admin.modifier_revue', compact('revue', 'bdIndexations'));
    }



    public function update(Request $request, $idRevue)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'ISSN' => 'required|string|max:255',
            'nomRevue' => 'required|string|max:255',
            'descRevue' => 'nullable|string',
            'typeRevue' => 'nullable|string|max:255',
            'bdIndexation' => 'nullable|array',
            'bdIndexation.*' => 'exists:bd_indexations,idBDIndex', // Correction du nom de la table
            'dateDebut' => 'nullable|array',
            'dateDebut.*' => 'nullable|date',
            'dateFin' => 'nullable|array',
            'dateFin.*' => 'nullable|date|after_or_equal:dateDebut.*',
        ]);

        // Récupération de la revue existante
        $revue = Revue::findOrFail($idRevue);

        // Mise à jour des champs principaux de la revue
        $revue->update([
            'ISSN' => $validatedData['ISSN'],
            'nomRevue' => $validatedData['nomRevue'],
            'descRevue' => $validatedData['descRevue'] ?? null, // Ajout de `null` pour les champs optionnels
            'typeRevue' => $validatedData['typeRevue'] ?? null,
        ]);

        // Mise à jour des relations avec les bases d'indexation
        if (isset($validatedData['bdIndexation'])) {
            $syncData = [];

            foreach ($validatedData['bdIndexation'] as $bdIndexId) {
                $syncData[$bdIndexId] = [
                    'dateDebut' => $validatedData['dateDebut'][$bdIndexId] ?? null,
                    'dateFin' => $validatedData['dateFin'][$bdIndexId] ?? null,
                ];
            }

            // Synchronisation des relations avec la table pivot
            $revue->bdIndexations()->sync($syncData);
        } else {
            // Si aucune base d'indexation n'est sélectionnée, on vide la relation
            $revue->bdIndexations()->detach();
        }

        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Revue mise à jour avec succès.');
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
