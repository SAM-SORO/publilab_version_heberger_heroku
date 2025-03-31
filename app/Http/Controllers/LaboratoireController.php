<?php

namespace App\Http\Controllers;

use App\Models\AxeRecherche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UMRI;
use App\Models\Laboratoire;
use App\Models\Chercheur;

class LaboratoireController extends Controller
{
    public function index()
    {
        // Récupérer les laboratoires avec toutes les relations nécessaires
        $laboratoires = Laboratoire::with([
            'directeur',
            'axesRecherches.themes',
            'chercheurs'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        // Récupérer les chercheurs pour le formulaire d'ajout
        $chercheurs = Chercheur::all();

        // Récupérer les UMRI pour le formulaire
        $umris = UMRI::all();

        // Récupérer les axes de recherche pour le formulaire
        $axesRecherche = AxeRecherche::all();

        return view('lab.admin.liste_laboratoire', compact('laboratoires', 'chercheurs', 'umris', 'axesRecherche'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'nomLabo' => 'required|string|max:255',
            'sigleLabo' => 'required|string|max:20',
            'anneeCreation' => 'nullable|string|max:4',
            'localisationLabo' => 'nullable|string|max:255',
            'adresseLabo' => 'nullable|string|max:255',
            'telLabo' => 'nullable|string|max:20',
            'faxLabo' => 'nullable|string|max:20',
            'emailLabo' => 'nullable|email|max:255',
            'descLabo' => 'nullable|string',
            'idUMRI' => 'nullable|exists:umris,idUMRI',
            'idDirecteurLabo' => 'nullable|exists:chercheurs,idCherch'
        ]);

        try {
            DB::beginTransaction();

            $laboratoire = Laboratoire::create($request->all());

            DB::commit();
            return redirect()->back()
                ->with('success', 'Laboratoire créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Récupérer le laboratoire avec toutes ses relations nécessaires
            $laboratoire = Laboratoire::with(['axesRecherches', 'umri', 'directeur'])
                ->findOrFail($id);

            // Récupérer les listes pour les selects
            $axesRecherches = AxeRecherche::all();
            $umris = UMRI::all();
            $chercheurs = Chercheur::orderBy('nomCherch')->get();

            // Retourner la vue avec les données
            return view('lab.admin.modifier_laboratoire',
                compact('laboratoire', 'axesRecherches', 'umris', 'chercheurs'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeLaboratoires')
                ->with('error', 'Une erreur est survenue lors de la récupération des données du laboratoire : ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // Si la recherche est vide, retourner à la liste
            if (empty($query)) {
                return redirect()->route('admin.listeLaboratoires');
            }

            $laboratoires = Laboratoire::with([
                'directeur',
                'axesRecherches.themes',
                'chercheurs'
            ])
            ->where(function($q) use ($query) {
                $q->where('nomLabo', 'like', '%' . $query . '%')
                  ->orWhere('sigleLabo', 'like', '%' . $query . '%')
                  ->orWhereHas('directeur', function($subQ) use ($query) {
                      $subQ->where(DB::raw("CONCAT(nomCherch, ' ', prenomCherch)"), 'like', '%' . $query . '%')
                           ->orWhere(DB::raw("CONCAT(prenomCherch, ' ', nomCherch)"), 'like', '%' . $query . '%');
                  });
            })
            ->orderBy('nomLabo', 'asc')
            ->paginate(10)
            ->withQueryString();

            // Récupérer les données pour les formulaires
            $chercheurs = Chercheur::all();
            $umris = UMRI::all();
            $axesRecherche = AxeRecherche::all();

            return view('lab.admin.liste_laboratoire', compact(
                'laboratoires',
                'chercheurs',
                'umris',
                'axesRecherche',
                'query'
            ));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeLaboratoires')
                ->with('error', 'Erreur lors de la recherche : ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nomLabo' => 'required|string|max:255',
            'sigleLabo' => 'required|string|max:20',
            'anneeCreation' => 'nullable|string|max:4',
            'localisationLabo' => 'nullable|string|max:255',
            'adresseLabo' => 'nullable|string|max:255',
            'telLabo' => 'nullable|string|max:20',
            'faxLabo' => 'nullable|string|max:20',
            'emailLabo' => 'nullable|email|max:255',
            'descLabo' => 'nullable|string',
            'idUMRI' => 'nullable|exists:umris,idUMRI',
            'idDirecteurLabo' => 'nullable|exists:chercheurs,idCherch'
        ]);

        try {
            DB::beginTransaction();

            $laboratoire = Laboratoire::findOrFail($id);
            $laboratoire->update($validated);

            DB::commit();
            return redirect()->route('admin.listeLaboratoires')
                ->with('success', 'Laboratoire modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $laboratoire = Laboratoire::findOrFail($id);

            // Vérifier les dépendances
            if ($laboratoire->chercheurs()->exists()) {
                throw new \Exception('Impossible de supprimer ce laboratoire car il a des chercheurs associés.');
            }

            // Pour les axes de recherche, mettre leur idLabo à null au lieu de les supprimer
            $laboratoire->axesRecherches()->update(['idLabo' => null]);

            // Supprimer le laboratoire
            $laboratoire->delete();

            DB::commit();
            return redirect()->route('admin.listeLaboratoires')
                ->with('success', 'Laboratoire supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.listeLaboratoires')
                ->with('error', $e->getMessage());
        }
    }
}
