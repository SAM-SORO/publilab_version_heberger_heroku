<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMRI;
use App\Models\EDP;
use Illuminate\Support\Facades\DB;

class UmrisController extends Controller
{
    public function index()
    {
        // Récupérer les UMRI triés par ordre décroissant (par exemple, sur la date de création)
        $umris = UMRI::orderByDesc('created_at')->paginate(10);

        // Récupérer tous les EDP
        $edps = EDP::all();

        // Retourner la vue avec les données
        return view('lab.admin.liste_umris', compact('umris', 'edps'));
    }



    public function create(Request $request)
    {
        // Validation des données envoyées dans le formulaire
        $request->validate([
            'nomUMRI' => 'required|string|max:255',
            'localisationUMI' => 'nullable|string|max:255',
            'WhatsAppUMRI' => 'nullable|regex:/^[0-9]+$/|max:255', // Validation numérique
            'emailUMRI' => 'nullable|email|max:255',
            'idEDP' => 'required|exists:edps,idEDP', // Vérifier si l'EDP existe
        ],[
            'nomUMRI.required' => 'Le nom de l\'UMRI est requis.',
            'nomUMRI.string' => 'Le nom de l\'UMRI doit être une chaîne de caractères.',
            'nomUMRI.max' => 'Le nom de l\'UMRI ne peut pas dépasser 255 caractères.',

            'localisationUMI.string' => 'La localisation doit être une chaîne de caractères.',
            'localisationUMI.max' => 'La localisation ne peut pas dépasser 255 caractères.',

            'WhatsAppUMRI.regex' => 'Le numéro WhatsApp doit être composé uniquement de chiffres.',
            'WhatsAppUMRI.max' => 'Le numéro WhatsApp ne peut pas dépasser 255 caractères.',

            'emailUMRI.email' => 'L\'adresse email doit être valide.',
            'emailUMRI.max' => 'L\'email ne peut pas dépasser 255 caractères.',

            'idEDP.required' => 'L\'EDP est requis.',
            'idEDP.exists' => 'L\'EDP sélectionné n\'existe pas.',
        ]);

        // Créer un nouvel UMRI
        UMRI::create([
            'nomUMRI' => $request->input('nomUMRI'),
            'localisationUMI' => $request->input('localisationUMI'),
            'WhatsAppUMRI' => $request->input('WhatsAppUMRI'),
            'emailUMRI' => $request->input('emailUMRI'),
            'idEDP' => $request->input('idEDP'),
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('admin.listeUmris')->with('success', 'UMRI enregistré avec succès.');
    }




    public function edit($id)
    {
        try {
            // Récupérer l'UMRI
            $umri = Umri::findOrFail($id);

            // Récupérer la liste des EDPs pour le select
            $edps = Edp::all();

            // Retourner la vue avec les données
            return view('lab.admin.modifier_umris', compact('umri', 'edps'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeUmris')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données de l\'UMRI.');
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
                'nomUMRI' => 'required|string|max:255',
                'localisationUMI' => 'nullable|string|max:255',
                'WhatsAppUMRI' => 'nullable|string|max:20',
                'emailUMRI' => 'required|email|max:255',
                'idEDP' => 'required|exists:edps,idEDP',
            ], [
                'nomUMRI.required' => 'Le nom de l\'UMRI est obligatoire.',
                'nomUMRI.max' => 'Le nom de l\'UMRI ne doit pas dépasser 255 caractères.',
                'localisationUMI.max' => 'La localisation ne doit pas dépasser 255 caractères.',
                'WhatsAppUMRI.max' => 'Le numéro WhatsApp ne doit pas dépasser 20 caractères.',
                'emailUMRI.required' => 'L\'adresse email est obligatoire.',
                'emailUMRI.email' => 'L\'adresse email doit être valide.',
                'emailUMRI.max' => 'L\'adresse email ne doit pas dépasser 255 caractères.',
                'idEDP.required' => 'L\'EDP est obligatoire.',
                'idEDP.exists' => 'L\'EDP sélectionné n\'existe pas.',
            ]);

            // Récupérer l'UMRI
            $umri = Umri::findOrFail($id);

            // Mettre à jour les informations de l'UMRI
            $umri->update([
                'nomUMRI' => $validated['nomUMRI'],
                'localisationUMI' => $validated['localisationUMI'],
                'WhatsAppUMRI' => $validated['WhatsAppUMRI'],
                'emailUMRI' => $validated['emailUMRI'],
                'idEDP' => $validated['idEDP'],
            ]);

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeUmris')
                ->with('success', 'UMRI modifié avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'UMRI : ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Effectuer la recherche par nomUMRI
        $umris = UMRI::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('nomUMRI', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Ajouter la pagination

        $edps = EDP::all();

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_umris', compact('umris', 'query' , 'edps'));
    }


    //supprimer un umris
    public function delete($id)
    {
        try {
            // Démarrer une transaction
            DB::beginTransaction();

            // Trouver l'UMRI à supprimer
            $umri = UMRI::findOrFail($id);

            // Vérifier si des laboratoires sont associés
            $laboratoires = $umri->laboratoires;
            if ($laboratoires->count() > 0) {
                DB::rollBack(); // Annuler la transaction
                return redirect()->back()->with('error', 'Impossible de supprimer l\'UMRI car il est associé à des laboratoires.');
            }

            // Supprimer l'UMRI
            $umri->delete();

            // Confirmer la transaction
            DB::commit();

            return redirect()->back()->with('success', 'L\'UMRI a été supprimé avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'UMRI. Détails : ' . $e->getMessage());
        }
    }
}
