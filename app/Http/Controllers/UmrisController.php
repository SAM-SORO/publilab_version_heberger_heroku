<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMRI;
use App\Models\EDP;
use App\Models\Chercheur;
use Illuminate\Support\Facades\DB;

class UmrisController extends Controller
{
    public function index()
    {
        // Récupérer les UMRI avec leurs relations
        $umris = UMRI::with(['directeur', 'edp'])->paginate(10);
        $edps = EDP::all();
        $chercheurs = Chercheur::all(); // Ajout des chercheurs pour le select
        return view('lab.admin.liste_umris', compact('umris', 'edps', 'chercheurs'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'sigleUMRI' => 'required|string|max:50|unique:umris,sigleUMRI',
            'nomUMRI' => 'required|string|max:255|unique:umris,nomUMRI',
            'localisationUMRI' => 'nullable|string|max:255',
            'idDirecteurUMRI' => 'nullable|exists:chercheurs,idCherch',
            'secretaireUMRI' => 'nullable|string|max:255',
            'contactSecretariatUMRI' => 'nullable|string|max:50',
            'emailSecretariatUMRI' => 'nullable|email|max:255'
        ]);

        try {
            // Opération simple, pas besoin de transaction
            UMRI::create($validated);

            return redirect()->route('admin.listeUmris')
                ->with('success', 'UMRI créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {

        try {
            $umri = UMRI::with(['directeur', 'edp'])->findOrFail($id);
            $edps = EDP::all();
            $chercheurs = Chercheur::all();

            return view('lab.admin.modifier_umris', compact('umri', 'edps', 'chercheurs'));
        } catch (\Exception $e) {
            return redirect()->route('admin.listeUmris')
                           ->with('error', 'Une erreur est survenue lors de la récupération des données de l\'UMRI.');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sigleUMRI' => 'required|string|max:50|unique:umris,sigleUMRI,' . $id . ',idUMRI',
            'nomUMRI' => 'required|string|max:255|unique:umris,nomUMRI,' . $id . ',idUMRI',
            'localisationUMRI' => 'nullable|string|max:255',
            'idDirecteurUMRI' => 'nullable|exists:chercheurs,idCherch',
            'secretaireUMRI' => 'nullable|string|max:255',
            'contactSecretariatUMRI' => 'nullable|string|max:50',
            'emailSecretariatUMRI' => 'nullable|email|max:255'
        ]);

        try {
            $umri = UMRI::findOrFail($id);
            $umri->update($validated); // Opération simple, pas besoin de transaction

            return redirect()->route('admin.listeUmris')
                ->with('success', 'UMRI modifiée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $umris = UMRI::with(['directeur', 'laboratoires'])
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('sigleUMRI', 'like', '%' . $query . '%')
                    ->orWhere('nomUMRI', 'like', '%' . $query . '%')
                    ->orWhere('localisationUMRI', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $edps = EDP::all();
        $chercheurs = Chercheur::all(); // Ajout des chercheurs pour le select

        return view('lab.admin.liste_umris', compact('umris', 'edps', 'chercheurs', 'query'));
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $umri = UMRI::findOrFail($id);

            // Vérifier les dépendances
            if ($umri->laboratoires()->exists()) {
                throw new \Exception('Impossible de supprimer cette UMRI car elle a des laboratoires associés.');
            }

            if ($umri->chercheurs()->exists()) {
                throw new \Exception('Impossible de supprimer cette UMRI car elle a des chercheurs associés.');
            }

            // Supprimer l'UMRI
            $umri->delete();

            DB::commit();
            return redirect()->route('admin.listeUmris')
                ->with('success', 'UMRI supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.listeUmris')
                ->with('error', $e->getMessage());
        }
    }
}
