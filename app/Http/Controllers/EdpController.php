<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EDP;
use Illuminate\Support\Facades\DB;

class EdpController extends Controller
{

    // Liste des EDP
    public function index()
    {
        // Récupérer tous les EDP
        $edps = EDP::paginate(5);

        // Retourner la vue avec les EDP
        return view('lab.admin.liste_edp', compact('edps'));
    }




    // EdpController.php

    public function create(Request $request)
    {
        // Validation des données envoyées dans le formulaire avec messages d'erreur personnalisés
        $request->validate([
            'nomEDP' => 'required|string|max:255',
            'localisationEDP' => 'nullable|string|max:255',
            'WhatsAppUMI' => 'nullable|regex:/^[0-9]+$/|max:255',  // Validation pour accepter uniquement des chiffres
            'emailUMI' => 'nullable|email|max:255',
        ], [
            'nomEDP.required' => 'Le nom de l\'EDP est requis.',
            'nomEDP.string' => 'Le nom de l\'EDP doit être une chaîne de caractères.',
            'nomEDP.max' => 'Le nom de l\'EDP ne peut pas dépasser 255 caractères.',
            'localisationEDP.string' => 'La localisation doit être une chaîne de caractères.',
            'localisationEDP.max' => 'La localisation ne peut pas dépasser 255 caractères.',
            'WhatsAppUMI.regex' => 'Le numéro WhatsApp doit contenir uniquement des chiffres.',
            'WhatsAppUMI.max' => 'Le numéro WhatsApp ne peut pas dépasser 255 caractères.',
            'emailUMI.email' => 'L\'email doit être une adresse email valide.',
            'emailUMI.max' => 'L\'email ne peut pas dépasser 255 caractères.',
        ]);


        // Créer un nouvel EDP avec les données soumises
        Edp::create([
            'nomEDP' => $request->input('nomEDP'),
            'localisationEDP' => $request->input('localisationEDP'),
            'WhatsAppUMI' => $request->input('WhatsAppUMI'),
            'emailUMI' => $request->input('emailUMI'),
        ]);

        // Rediriger vers la liste des EDPs avec un message de succès
        return redirect()->route('admin.listeEdp')->with('success', 'EDP enregistré avec succès.');
    }


    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Effectuer la recherche dans le champ 'nomEDP'
        $edps = EDP::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('nomEDP', 'like', '%' . $query . '%')
                                ->orWhere('localisationEDP', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Ajouter la pagination pour les résultats

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_edp', compact('edps', 'query'));
    }


    public function edit($id)
    {
        try {
            // Récupérer l'EDP
            $edp = Edp::findOrFail($id);

            // Retourner la vue avec les données
            return view('lab.admin.modifier_edp', compact('edp'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeEdp')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données de l\'EDP.');
        }
    }

    public function update(Request $request, $id)
    {
        // Début de la transaction
        DB::beginTransaction();

        try {
            // Validation des données
            $validated = $request->validate([
                'nomEDP' => 'required|string|max:255',
                'localisationEDP' => 'nullable|string|max:255',
                'WhatsAppUMI' => 'nullable|regex:/^[0-9]+$/|max:255',
                'emailUMI' => 'nullable|email|max:255',
            ], [
                'nomEDP.required' => 'Le nom de l\'EDP est requis.',
                'nomEDP.string' => 'Le nom de l\'EDP doit être une chaîne de caractères.',
                'nomEDP.max' => 'Le nom de l\'EDP ne peut pas dépasser 255 caractères.',
                'localisationEDP.string' => 'La localisation doit être une chaîne de caractères.',
                'localisationEDP.max' => 'La localisation ne peut pas dépasser 255 caractères.',
                'WhatsAppUMI.regex' => 'Le numéro WhatsApp doit contenir uniquement des chiffres.',
                'WhatsAppUMI.max' => 'Le numéro WhatsApp ne peut pas dépasser 255 caractères.',
                'emailUMI.email' => 'L\'email doit être une adresse email valide.',
                'emailUMI.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            ]);

            // Récupérer l'EDP
            $edp = Edp::findOrFail($id);

            // Mettre à jour les informations de l'EDP
            $edp->update([
                'nomEDP' => $validated['nomEDP'],
                'localisationEDP' => $validated['localisationEDP'],
                'WhatsAppUMI' => $validated['WhatsAppUMI'],
                'emailUMI' => $validated['emailUMI'],
            ]);

            // Commit de la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->route('admin.listeEdp')
                ->with('success', 'EDP modifié avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'EDP : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Démarrer une transaction
            DB::beginTransaction();

            // Trouver l'EDP à supprimer
            $edp = EDP::findOrFail($id);

            // Vérifier si des UMRIs sont associés à cet EDP
            if ($edp->hasDependencies()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Impossible de supprimer l\'EDP car il est associé à des UMRIs.');
            }


            // Supprimer l'EDP
            $edp->delete();

            // Confirmer la transaction
            DB::commit();

            return redirect()->back()->with('success', 'L\'EDP a été supprimé avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'EDP. Détails : ' . $e->getMessage());
        }
    }
}
