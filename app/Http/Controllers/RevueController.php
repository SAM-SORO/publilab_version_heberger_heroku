<?php

namespace App\Http\Controllers;

use App\Models\Revue;
use App\Models\BdIndexation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevueController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'nomRevue' => 'required|string|max:255|unique:revues,nomRevue',
            'issn' => 'nullable|string|max:20',
            'facteurImpact' => 'nullable|numeric|min:0',
            'editeur' => 'nullable|string|max:255',
            'bdIndexations' => 'nullable|array',
            'bdIndexations.*' => 'exists:bd_indexations,idBDInd'
        ]);

        DB::beginTransaction(); // Garder la transaction car on a une relation many-to-many

        try {
            $revue = Revue::create([
                'nomRevue' => $validated['nomRevue'],
                'issn' => $validated['issn'],
                'facteurImpact' => $validated['facteurImpact'],
                'editeur' => $validated['editeur']
            ]);

            // Attacher les bases d'indexation
            if (isset($validated['bdIndexations'])) {
                $revue->bdIndexations()->attach($validated['bdIndexations']);
            }

            DB::commit();
            return redirect()->route('admin.listeRevues')
                ->with('success', 'Revue créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nomRevue' => 'required|string|max:255|unique:revues,nomRevue,' . $id . ',idRevue',
            'issn' => 'nullable|string|max:20',
            'facteurImpact' => 'nullable|numeric|min:0',
            'editeur' => 'nullable|string|max:255',
            'bdIndexations' => 'nullable|array',
            'bdIndexations.*' => 'exists:bd_indexations,idBDInd'
        ]);

        DB::beginTransaction(); // Garder la transaction car on a une relation many-to-many

        try {
            $revue = Revue::findOrFail($id);
            $revue->update($validated);

            // Synchroniser les bases d'indexation
            $revue->bdIndexations()->sync(
                isset($validated['bdIndexations']) ? $validated['bdIndexations'] : []
            );

            DB::commit();
            return redirect()->route('admin.listeRevues')
                ->with('success', 'Revue modifiée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction(); // Garder la transaction car on manipule des relations

        try {
            $revue = Revue::findOrFail($id);

            // Vérifier si la revue a des articles
            if ($revue->articles()->exists()) {
                throw new \Exception('Impossible de supprimer cette revue car elle contient des articles.');
            }

            // Détacher les relations
            $revue->bdIndexations()->detach();
            $revue->delete();

            DB::commit();
            return redirect()->route('admin.listeRevues')
                ->with('success', 'Revue supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
