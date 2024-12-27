<?php

namespace App\Http\Controllers;

use App\Models\AxeRecherche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UMRI;
use App\Models\Laboratoire;

class LaboratoireController extends Controller
{
    public function index()
    {
        // Récupérer les laboratoires triés par ordre décroissant (par exemple, selon la date de création)
        $laboratoires = Laboratoire::with('axesRecherches')
            ->orderByDesc('created_at') // Trier par la colonne created_at en ordre décroissant
            ->paginate(10);

        // Récupérer tous les UMRI pour l'affichage dans la vue
        $umris = UMRI::all();

        // Récupérer tous les axes de recherche pour l'affichage dans la vue
        $axesRecherche = AxeRecherche::all();

        // Retourner la vue avec les données récupérées
        return view('lab.admin.liste_laboratoire', compact('laboratoires', 'umris', 'axesRecherche'));
    }




    public function create(Request $request)
    {
        // Validation des données envoyées
        $request->validate([
            'nomLabo' => 'required|string|max:255',
            'anneeCreation' => 'nullable|string|max:255',
            'localisationLabo' => 'nullable|string|max:255',
            'adresseLabo' => 'nullable|string|max:255',
            'telLabo' => 'nullable|string|max:255',
            'faxLabo' => 'nullable|string|max:255',
            'emailLabo' => 'required|email|max:255',
            'idUMRI' => 'required|exists:umris,idUMRI',
            'axesRecherche' => 'nullable|array', // Vérifie si c'est un tableau
            'axesRecherche.*' => 'exists:axe_recherches,idAxeRech', // Chaque valeur doit exister dans la table axes
        ], [
            'nomLabo.required' => 'Le nom du laboratoire est requis.',
            'emailLabo.required' => 'L’email est requis.',
            'idUMRI.required' => 'L’UMRI est requis.',
            'axesRecherche.*.exists' => 'Un ou plusieurs axes de recherche sont invalides.',
        ]);

        DB::beginTransaction(); // Démarre une transaction

        try {
            // Création du laboratoire
            $laboratoire = new Laboratoire();
            $laboratoire->nomLabo = $request->nomLabo;
            $laboratoire->anneeCreation = $request->anneeCreation;
            $laboratoire->localisationLabo = $request->localisationLabo;
            $laboratoire->adresseLabo = $request->adresseLabo;
            $laboratoire->telLabo = $request->telLabo;
            $laboratoire->faxLabo = $request->faxLabo;
            $laboratoire->emailLabo = $request->emailLabo;
            $laboratoire->idUMRI = $request->idUMRI;
            $laboratoire->save(); // Enregistre le laboratoire dans la base

            // Association avec les axes de recherche (si présents)
            if ($request->has('axesRecherche')) {
                $laboratoire->axesRecherche()->sync($request->axesRecherche); // Synchronisation avec les axes
            }

            DB::commit(); // Valide la transaction
            return redirect()->back()->with('success', 'Le laboratoire a été enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack(); // Annule la transaction en cas d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l’enregistrement : ' . $e->getMessage());
        }
    }


    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        $umris = UMRI::all(); // Récupérer tous les UMRI pour l'affichage

        // Récupérer tous les axes de recherche pour l'affichage dans la vue
        $axesRecherche = AxeRecherche::all();

        // Effectuer la recherche dans le champ 'nomLabo'
        $laboratoires = Laboratoire::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('nomLabo', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Ajouter la pagination pour les résultats

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_laboratoire', compact('laboratoires', 'query', 'umris', 'axesRecherche'));
    }


    public function edit($id)
    {
        try {
            // Récupérer le laboratoire avec ses relations
            $laboratoire = Laboratoire::with('axesRecherches')->find($id);

            // Récupérer les listes pour les selects
            $axesRecherches = AxeRecherche::all();
            $umris = Umri::all();

            // Retourner la vue avec les données
            return view('lab.admin.modifier_laboratoire', compact('laboratoire', 'axesRecherches', 'umris'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeLaboratoires')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données du laboratoire.'. $e->getMessage());
        }
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
                'nomLabo' => 'required|string|max:255',
                'anneeCreation' => 'nullable|string|max:4',
                'localisationLabo' => 'nullable|string|max:255',
                'adresseLabo' => 'nullable|string|max:255',
                'telLabo' => 'nullable|string|max:20',
                'faxLabo' => 'nullable|string|max:20',
                'emailLabo' => 'required|email|max:255',
                'descLabo' => 'nullable|string',
                'idUMRI' => 'required|exists:umris,idUMRI',
                'axesRecherche' => 'nullable|array',
                'axesRecherche.*' => 'exists:axe_recherches,idAxeRech',
            ], [
                'nomLabo.required' => 'Le nom du laboratoire est obligatoire.',
                'nomLabo.max' => 'Le nom du laboratoire ne doit pas dépasser 255 caractères.',
                'anneeCreation.max' => 'L\'année de création ne doit pas dépasser 4 caractères.',
                'emailLabo.required' => 'L\'adresse email est obligatoire.',
                'emailLabo.email' => 'L\'adresse email doit être valide.',
                'emailLabo.max' => 'L\'adresse email ne doit pas dépasser 255 caractères.',
                'telLabo.max' => 'Le numéro de téléphone ne doit pas dépasser 20 caractères.',
                'faxLabo.max' => 'Le numéro de fax ne doit pas dépasser 20 caractères.',
                'adresseLabo.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',
                'localisationLabo.max' => 'La localisation ne doit pas dépasser 255 caractères.',
                'idUMRI.required' => 'L\'UMRI est obligatoire.',
                'idUMRI.exists' => 'L\'UMRI sélectionnée n\'existe pas.',
                'axesRecherche.*.exists' => 'Un des axes de recherche sélectionnés n\'existe pas.',
            ]);

            // Récupérer le laboratoire
            $laboratoire = Laboratoire::findOrFail($id);

            // Mettre à jour les informations du laboratoire
            $laboratoire->update([
                'nomLabo' => $validated['nomLabo'],
                'anneeCreation' => $validated['anneeCreation'],
                'localisationLabo' => $validated['localisationLabo'],
                'adresseLabo' => $validated['adresseLabo'],
                'telLabo' => $validated['telLabo'],
                'faxLabo' => $validated['faxLabo'],
                'emailLabo' => $validated['emailLabo'],
                'descLabo' => $validated['descLabo'],
                'idUMRI' => $validated['idUMRI'],
            ]);

            // Mettre à jour les axes de recherche
            if (isset($validated['axesRecherche'])) {
                $laboratoire->axesRecherche()->sync($validated['axesRecherche']);
            }

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeLaboratoires')
                ->with('success', 'Laboratoire modifié avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification du laboratoire : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Trouver le laboratoire à supprimer
            $laboratoire = Laboratoire::findOrFail($id);

            // Vérifier s'il y a des chercheurs associés
            if ($laboratoire->chercheurs()->exists()) {
                return redirect()->back()->with(
                    'error',
                    'Le laboratoire ne peut pas être supprimé car il a des chercheurs associés. Veuillez les gérer avant de continuer.'
                );
            }

            // Détacher les relations avec les axes de recherche (table pivot)
            $laboratoire->axeRecherches()->detach();

            // Supprimer le laboratoire
            $laboratoire->delete();

            return redirect()->back()->with('success', 'Le laboratoire a été supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la suppression du laboratoire. Détails : ' . $e->getMessage()
            );
        }
    }


}
