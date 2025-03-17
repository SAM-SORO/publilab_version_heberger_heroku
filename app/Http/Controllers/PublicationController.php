<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\TypePublication;
use App\Models\BdIndexation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        // Récupérer les publications avec leurs relations
        $publications = Publication::with([
            'typePublication',
            'articles',
            'bdIndexations'  // Ajout de la relation bdIndexations
        ])
        ->when($filter, function ($query) use ($filter) {
            if (is_numeric($filter)) {
                return $query->where('idTypePub', $filter);
            }
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        // Récupérer tous les types de publications pour le formulaire d'ajout et le filtre
        $typesPublications = TypePublication::all();

        // Récupérer toutes les bases d'indexation pour le formulaire d'ajout
        $bdIndexations = BdIndexation::all();

        return view('lab.admin.liste_publication',
            compact('publications', 'typesPublications', 'bdIndexations', 'filter'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'titrePub' => 'required|string|max:255',
            'descPub' => 'nullable|string',
            'ISSN' => 'nullable|string|max:20',
            'editeurPub' => 'nullable|string|max:200',
            'idTypePub' => 'required|exists:type_publications,idTypePub',
            'bdIndexations' => 'nullable|array',
            'bdIndexations.*' => 'exists:bd_indexations,idBDIndex',
            'dateDebut' => 'nullable|array',
            'dateDebut.*' => 'nullable|date',
            'dateFin' => 'nullable|array',
            'dateFin.*' => 'nullable|date|after_or_equal:dateDebut.*'
        ], [
            'titrePub.required' => 'Le titre est obligatoire',
            'titrePub.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'ISSN.max' => 'L\'ISSN ne doit pas dépasser 20 caractères',
            'editeurPub.max' => 'Le nom de l\'éditeur ne doit pas dépasser 200 caractères'
        ]);

        DB::beginTransaction();

        try {
            // Créer la publication
            $publication = Publication::create([
                'titrePub' => $validated['titrePub'],
                'descPub' => $validated['descPub'],
                'ISSN' => $validated['ISSN'],
                'editeurPub' => $validated['editeurPub'],
                'idTypePub' => $validated['idTypePub']
            ]);

            // Attacher les bases d'indexation avec leurs dates
            if (!empty($validated['bdIndexations'])) {
                foreach ($validated['bdIndexations'] as $bdId) {
                    $publication->bdIndexations()->attach($bdId, [
                        'dateDebut' => $validated['dateDebut'][$bdId] ?? null,
                        'dateFin' => $validated['dateFin'][$bdId] ?? null
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.listePublications')
                ->with('success', 'Publication ajoutée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout de la publication : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)

    {

        $validated = $request->validate([
            'titrePub' => 'required|string|max:255',
            'descPub' => 'nullable|string',
            'ISSN' => 'nullable|string|max:255',
            'editeurPub' => 'nullable|string|max:200',
            'idTypePub' => 'required|exists:type_publications,idTypePub',
            'bdIndexations' => 'nullable|array',
            'bdIndexations.*' => 'exists:bd_indexations,idBDIndex',
            'dateDebut' => 'nullable|array',
            'dateDebut.*' => 'nullable|date',
            'dateFin' => 'nullable|array',
            'dateFin.*' => 'nullable|date|after_or_equal:dateDebut.*'
        ], [
            'titrePub.required' => 'Le titre est obligatoire',
            'titrePub.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'ISSN.max' => 'L\'ISSN ne doit pas dépasser 255 caractères',
            'editeurPub.max' => 'Le nom de l\'éditeur ne doit pas dépasser 200 caractères'
        ]);

        DB::beginTransaction();

        try {
            $publication = Publication::findOrFail($id);

            // Mettre à jour les informations de base
            $publication->update([
                'titrePub' => $validated['titrePub'],
                'descPub' => $validated['descPub'],
                'ISSN' => $validated['ISSN'],
                'editeurPub' => $validated['editeurPub'],
                'idTypePub' => $validated['idTypePub']
            ]);

            // Synchroniser les bases d'indexation avec leurs dates
            $syncData = [];
            if (!empty($validated['bdIndexations'])) {
                foreach ($validated['bdIndexations'] as $bdId) {
                    $syncData[$bdId] = [
                        'dateDebut' => $validated['dateDebut'][$bdId] ?? null,
                        'dateFin' => $validated['dateFin'][$bdId] ?? null
                    ];
                }
            }
            $publication->bdIndexations()->sync($syncData);

            DB::commit();
            return redirect()->route('admin.listePublications')
                ->with('success', 'Publication modifiée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $publication = Publication::findOrFail($id);

            // Vérifier si la publication a des articles associés
            if ($publication->articles()->exists()) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette publication car elle contient des articles.');
            }

            $publication->delete(); // Opération simple, pas besoin de transaction

            return redirect()->route('admin.listePublications')
                ->with('success', 'Publication supprimée avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la publication : ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        $publications = Publication::with(['typePublication', 'articles'])
            ->where(function($q) use ($query) {
                $q->where('titrePub', 'like', "%{$query}%")
                  ->orWhere('descPub', 'like', "%{$query}%")
                  ->orWhere('ISSN', 'like', "%{$query}%")
                  ->orWhere('editeurPub', 'like', "%{$query}%")
                  ->orWhereHas('typePublication', function($q) use ($query) {
                      $q->where('libeleTypePub', 'like', "%{$query}%");
                  });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $typesPublications = TypePublication::all();

        return view('lab.admin.liste_publication', compact('publications', 'typesPublications', 'query'));
    }

    /**
     * Afficher le formulaire de modification d'une publication
     */
    public function edit($id)
    {
        try {
            $publication = Publication::with(['typePublication', 'articles', 'bdIndexations'])
                ->findOrFail($id);
            $typesPublications = TypePublication::all();
            $bdIndexations = BdIndexation::all();

            return view('lab.admin.modifier_publication',
                compact('publication', 'typesPublications', 'bdIndexations'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listePublications')
                ->with('error', 'Erreur lors de la récupération de la publication : ' . $e->getMessage());
        }
    }
}
