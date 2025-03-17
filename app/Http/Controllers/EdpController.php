<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EDP;
use Illuminate\Support\Facades\DB;
use App\Models\Chercheur;

class EdpController extends Controller
{

    // Liste des EDP
    public function index()
    {
        $edps = EDP::with(['directeur'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $chercheurs = Chercheur::orderBy('nomCherch')->get();

        return view('lab.admin.liste_edp', compact('edps', 'chercheurs'));
    }


    // EdpController.php

    public function create(Request $request)
    {
        $validated = $request->validate([
            'sigleEDP' => 'required|string|max:50|unique:edps,sigleEDP',
            'nomEDP' => 'required|string|max:255|unique:edps,nomEDP',
            'localisationEDP' => 'nullable|string|max:255',
            'idDirecteurEDP' => 'nullable|exists:chercheurs,idCherch',
            'secretaireEDP' => 'nullable|string|max:255',
            'contactSecretariatEDP' => 'nullable|string|max:50',
            'emailSecretariatEDP' => 'nullable|email|max:255'
        ]);

        try {
            EDP::create($validated);
            return redirect()->route('admin.listeEdp')
                ->with('success', 'EDP créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }



    public function edit($id)
    {
        try {
            $edp = EDP::with(['directeur'])
                ->findOrFail($id);
            $chercheurs = Chercheur::orderBy('nomCherch')->get();

            return view('lab.admin.modifier_edp', compact('edp', 'chercheurs'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeEdp')
                ->with('error', 'EDP non trouvé.');
        }
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        $edps = EDP::with(['directeur'])
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function($q) use ($query) {
                    $q->where('sigleEDP', 'like', '%' . $query . '%')
                      ->orWhere('nomEDP', 'like', '%' . $query . '%')
                      ->orWhere('localisationEDP', 'like', '%' . $query . '%')
                      ->orWhere('secretaireEDP', 'like', '%' . $query . '%')
                      ->orWhere('emailSecretariatEDP', 'like', '%' . $query . '%');
                });
            })
            ->orderBy('nomEDP', 'asc')
            ->paginate(10);

        $chercheurs = Chercheur::orderBy('nomCherch')->get();

        return view('lab.admin.liste_edp', compact('edps', 'chercheurs', 'query'));
    }


    public function update(Request $request, $id)
    {
        try {
            $edp = EDP::findOrFail($id);

            $validated = $request->validate([
                'sigleEDP' => 'required|string|max:50|unique:edps,sigleEDP,' . $id . ',idEDP',
                'nomEDP' => 'required|string|max:255|unique:edps,nomEDP,' . $id . ',idEDP',
                'localisationEDP' => 'nullable|string|max:255',
                'idDirecteurEDP' => 'nullable|exists:chercheurs,idCherch',
                'secretaireEDP' => 'nullable|string|max:255',
                'contactSecretariatEDP' => 'nullable|string|max:50',
                'emailSecretariatEDP' => 'nullable|email|max:255'
            ]);

            $edp->update($validated);

            return redirect()->route('admin.listeEdp')
                ->with('success', 'EDP modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $edp = EDP::findOrFail($id);

            if ($edp->hasDependencies()) {
                throw new \Exception('Impossible de supprimer cet EDP car il est associé à des UMRIs.');
            }

            $edp->delete();
            return redirect()->route('admin.listeEdp')->with('success', 'EDP supprimé avec succès.');

        } catch (\Exception $e) {

            return redirect()->route('admin.listeEdp')
                ->with('error', $e->getMessage());
        }
    }
}


