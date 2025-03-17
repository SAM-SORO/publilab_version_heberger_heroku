<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chercheur;
use App\Models\Grade;
use App\Models\Laboratoire;
use App\Models\UMRI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class LaboChercheurController extends Controller
{

    public function index()
    {
        // Récupérer les chercheurs avec leurs grades et leur laboratoire associés, triés par date de création en ordre décroissant
        $chercheurs = Chercheur::with(['grades', 'laboratoires', 'umri'])
            ->orderByDesc('created_at') // Tri par date de création (vous pouvez changer ce champ si nécessaire)
            ->paginate(10); // Pagination avec 10 résultats par page

        // Récupérer tous les grades disponibles
        $grades = Grade::all();

        // Récupérer tous les laboratoires disponibles
        $laboratoires = Laboratoire::all();

        // Récupérer tous les UMRI disponibles
        $umris = UMRI::all();

        // Retourner la vue avec les chercheurs, grades, laboratoires et UMRI
        return view('lab.admin.liste_chercheur', compact('chercheurs', 'grades', 'laboratoires', 'umris'));
    }



    public function create(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nomCherch' => 'required|string|max:30',
            'prenomCherch' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            // Champs optionnels
            'genreCherch' => 'nullable|in:M,F',
            'matriculeCherch' => 'required|string|max:20',
            'emailCherch' => 'nullable|email|unique:chercheurs,emailCherch',
            'emploiCherch' => 'nullable|string|max:50',
            'departementCherch' => 'nullable|string|max:100',
            'fonctionAdministrativeCherch' => 'nullable|string|max:100',
            'specialiteCherch' => 'nullable|string|max:50',
            'dateNaissCherch' => 'nullable|date',
            'dateArriveeCherch' => 'nullable|date',
            'telCherch' => 'nullable|string|max:30',
            'idUMRI' => 'nullable|exists:umris,idUMRI',
            'laboratoires' => 'nullable|array',
            'laboratoires.*' => 'exists:laboratoires,idLabo',
            'dateAffectation' => 'nullable|array',
            'dateAffectation.*' => 'nullable|date'
        ], [
            'nomCherch.required' => 'Le nom est obligatoire.',
            'prenomCherch.required' => 'Le prénom est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.'
        ]);

        DB::beginTransaction();
        try {
            // Création du chercheur avec l'UMRI
            $chercheur = Chercheur::create([
                'nomCherch' => $validated['nomCherch'],
                'prenomCherch' => $validated['prenomCherch'],
                'password' => Hash::make($validated['password']),
                'genreCherch' => $validated['genreCherch'] ?? null,
                'matriculeCherch' => $validated['matriculeCherch'] ?? null,
                'emailCherch' => $validated['emailCherch'] ?? null,
                'emploiCherch' => $validated['emploiCherch'] ?? null,
                'departementCherch' => $validated['departementCherch'] ?? null,
                'fonctionAdministrativeCherch' => $validated['fonctionAdministrativeCherch'] ?? null,
                'specialiteCherch' => $validated['specialiteCherch'] ?? null,
                'dateNaissCherch' => $validated['dateNaissCherch'] ?? null,
                'dateArriveeCherch' => $validated['dateArriveeCherch'] ?? null,
                'telCherch' => $validated['telCherch'] ?? null,
                'idUMRI' => $validated['idUMRI'] ?? null
            ]);

            // Attacher les laboratoires avec leurs dates d'affectation si présents
            if (!empty($validated['laboratoires'])) {
                foreach ($validated['laboratoires'] as $index => $laboId) {
                    $dateAffectation = isset($validated['dateAffectation'][$laboId])
                        ? $validated['dateAffectation'][$laboId]
                        : now();

                    $chercheur->laboratoires()->attach($laboId, [
                        'dateAffectation' => $dateAffectation,
                        'niveau' => $index + 1
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.listeChercheurs')
                ->with('success', 'Chercheur ajouté avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }



    public function addGrade(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'chercheurId' => 'required|exists:chercheurs,idCherch',
            'grades' => 'required|array',
            'grades.*' => 'exists:grades,idGrade', // Vérifie que chaque grade existe
            'dates' => 'array', // Valide le tableau des dates
            'dates.*' => 'nullable|date', // Chaque date doit être valide
        ], [
            'grades.required' => 'Vous devez sélectionner au moins un grade.',
            'grades.*.exists' => 'Un grade sélectionné est invalide.',
            'dates.*.date' => 'Chaque date doit être valide.',
        ]);

        // Récupération du chercheur
        $chercheur = Chercheur::find($validated['chercheurId']);

        if (!$chercheur) {
            return redirect()->back()->with('error', 'Chercheur non trouvé.');
        }

        try {
            // Parcourir les grades sélectionnés
            foreach ($validated['grades'] as $gradeId) {
                // Récupérer la date associée au grade (s'il y en a une)
                $dateGrade = $validated['dates'][$gradeId] ?? null;

                // Vérifier si le grade est déjà associé au chercheur
                if (!$chercheur->grades->contains($gradeId)) {
                    // Ajouter le grade avec la date dans la table pivot
                    $chercheur->grades()->attach($gradeId, ['dateGrade' => $dateGrade]);
                }
            }

            // Retourner un succès
            return redirect()->route('admin.listeChercheurs')
                ->with('success', 'Grade(s) ajouté(s) avec succès.');
        } catch (\Exception $e) {
            // Gérer les erreurs
            return redirect()->route('admin.listeChercheurs')
                ->with('error', 'Une erreur est survenue, les grades n\'ont pas pu être ajoutés.');
        }
    }


    /**
     * Afficher le formulaire de modification d'un chercheur
     */
    public function edit($id)
    {
        try {
            // Récupérer le chercheur avec ses relations
            $chercheur = Chercheur::with(['grades', 'laboratoires', 'umri'])
                ->findOrFail($id);

            // Récupérer tous les grades, laboratoires et UMRI pour les sélecteurs
            $grades = Grade::all();
            $laboratoires = Laboratoire::all();
            $umris = UMRI::all();

            return view('lab.admin.modifier_chercheur', compact(
                'chercheur',
                'grades',
                'laboratoires',
                'umris'
            ));
        } catch (\Exception $e) {
            return redirect()->route('admin.listeChercheurs')
                ->with('error', 'Erreur lors de la récupération du chercheur : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un chercheur
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'nomCherch' => 'required|string|max:30',
            'prenomCherch' => 'required|string|max:100',
            'genreCherch' => 'nullable|in:M,F',
            'matriculeCherch' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'emploiCherch' => 'nullable|string|max:50',
            'departementCherch' => 'nullable|string|max:100',
            'fonctionAdministrativeCherch' => 'nullable|string|max:100',
            'specialiteCherch' => 'nullable|string|max:50',
            'emailCherch' => 'nullable|email|max:100|unique:chercheurs,emailCherch,'.$id.',idCherch',
            'dateNaissCherch' => 'nullable|date',
            'dateArriveeCherch' => 'nullable|date',
            'telCherch' => 'nullable|string|max:30',
            'idUMRI' => 'nullable|exists:umris,idUMRI',
            'laboratoires' => 'nullable|array',
            'laboratoires.*' => 'exists:laboratoires,idLabo',
            'grades' => 'nullable|array',
            'grades.*' => 'exists:grades,idGrade',
            'dates' => 'nullable|array',
            'dates.*' => 'nullable|date'
        ]);

        DB::beginTransaction();

        try {
            $chercheur = Chercheur::findOrFail($id);

            // Mettre à jour les informations de base
            $chercheur->nomCherch = $validated['nomCherch'];
            $chercheur->prenomCherch = $validated['prenomCherch'];
            $chercheur->genreCherch = $validated['genreCherch'] ?? null;
            $chercheur->matriculeCherch = $validated['matriculeCherch'] ?? null;
            $chercheur->emploiCherch = $validated['emploiCherch'] ?? null;
            $chercheur->departementCherch = $validated['departementCherch'] ?? null;
            $chercheur->fonctionAdministrativeCherch = $validated['fonctionAdministrativeCherch'] ?? null;
            $chercheur->specialiteCherch = $validated['specialiteCherch'] ?? null;
            $chercheur->emailCherch = $validated['emailCherch'];
            $chercheur->dateNaissCherch = $validated['dateNaissCherch'] ?? null;
            $chercheur->dateArriveeCherch = $validated['dateArriveeCherch'] ?? null;
            $chercheur->telCherch = $validated['telCherch'] ?? null;
            $chercheur->idUMRI = $validated['idUMRI'] ?? null;

            // Mettre à jour le mot de passe si fourni
            if (!empty($validated['password'])) {
                $chercheur->password = Hash::make($validated['password']);
            }

            $chercheur->save();

            // Mettre à jour les laboratoires
            if (isset($validated['laboratoires'])) {
                $chercheur->laboratoires()->sync($validated['laboratoires']);
            } else {
                $chercheur->laboratoires()->detach();
            }

            // Mettre à jour les grades avec leurs dates
            if (isset($validated['grades'])) {
                $gradesWithDates = [];
                foreach ($validated['grades'] as $gradeId) {
                    $gradesWithDates[$gradeId] = [
                        'dateGrade' => $validated['dates'][$gradeId] ?? now()
                    ];
                }
                $chercheur->grades()->sync($gradesWithDates);
            } else {
                $chercheur->grades()->detach();
            }

            DB::commit();
            return redirect()->route('admin.listeChercheurs')
                ->with('success', 'Chercheur modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }




    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // Base query avec les relations nécessaires
            $chercheurs = Chercheur::with(['grades', 'laboratoires', 'umri']);

            if ($query) {
                // Si la recherche contient un espace, on considère que c'est une recherche par nom complet
                if (str_contains($query, ' ')) {
                    $parts = explode(' ', $query);
                    $nom = $parts[0];
                    $prenom = $parts[1] ?? '';

                    $chercheurs->where(function($q) use ($nom, $prenom) {
                        // Recherche exacte sur nom et prénom
                        $q->where(function($subQ) use ($nom, $prenom) {
                            $subQ->where('nomCherch', 'like', '%' . $nom . '%')
                                 ->where('prenomCherch', 'like', '%' . $prenom . '%');
                        })
                        // OU recherche inversée (prénom nom)
                        ->orWhere(function($subQ) use ($nom, $prenom) {
                            $subQ->where('prenomCherch', 'like', '%' . $nom . '%')
                                 ->where('nomCherch', 'like', '%' . $prenom . '%');
                        });
                    });
                } else {
                    // Recherche simple sur un seul terme
                    $chercheurs->where(function($q) use ($query) {
                        $q->where('nomCherch', 'like', '%' . $query . '%')
                          ->orWhere('prenomCherch', 'like', '%' . $query . '%')
                          ->orWhere('matriculeCherch', 'like', '%' . $query . '%')
                          ->orWhere('emailCherch', 'like', '%' . $query . '%')
                          ->orWhereHas('laboratoires', function($subQ) use ($query) {
                              $subQ->where('sigleLabo', 'like', '%' . $query . '%')
                                   ->orWhere('nomLabo', 'like', '%' . $query . '%');
                          })
                          ->orWhereHas('grades', function($subQ) use ($query) {
                              $subQ->where('sigleGrade', 'like', '%' . $query . '%')
                                   ->orWhere('nomGrade', 'like', '%' . $query . '%');
                          })
                          ->orWhereHas('umri', function($subQ) use ($query) {
                              $subQ->where('sigleUMRI', 'like', '%' . $query . '%')
                                   ->orWhere('nomUMRI', 'like', '%' . $query . '%');
                          });
                    });
                }
            }

            // Pagination des résultats
            $chercheurs = $chercheurs->orderByDesc('created_at')->paginate(10);

            // Récupérer les données nécessaires pour le formulaire
            $grades = Grade::all();
            $laboratoires = Laboratoire::all();
            $umris = UMRI::all();

            return view('lab.admin.liste_chercheur', compact('chercheurs', 'grades', 'laboratoires', 'umris', 'query'));

        } catch (\Exception $e) {
            return redirect()->route('admin.listeChercheurs')
                ->with('error', 'Erreur lors de la recherche : ' . $e->getMessage());
        }
    }



    //supprimer un cherche
    public function delete($id)
    {
        try {
            // Trouver le chercheur à supprimer
            $chercheur = Chercheur::findOrFail($id);

            // Vérifier s'il a des articles ou des doctorants encadrés
            if ($chercheur->articles()->exists() || $chercheur->doctorantsEncadres()->exists()) {
                return redirect()->back()->with(
                    'error',
                    'Suppression impossible : ce chercheur est associé à des articles ou des doctorants. Veuillez supprimer ou réassigner ces éléments avant de continuer.'
                );
            }

            // Démarrer une transaction
            DB::beginTransaction();

            // Supprimer les relations many-to-many
            $chercheur->laboratoires()->detach();
            $chercheur->grades()->detach();

            // Supprimer le chercheur
            $chercheur->delete();

            // Confirmer la transaction
            DB::commit();

            return redirect()->back()->with('success', 'Le chercheur a été supprimé avec succès.');
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la suppression du chercheur. Détails : ' . $e->getMessage()
            );
        }
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nomCherch' => 'required|string|max:30',
            'prenomCherch' => 'required|string|max:100',
            'genreCherch' => 'nullable|in:M,F',
            'matriculeCherch' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'emploiCherch' => 'nullable|string|max:50',
            'departementCherch' => 'nullable|string|max:100',
            'fonctionAdministrativeCherch' => 'nullable|string|max:100',
            'specialiteCherch' => 'nullable|string|max:50',
            'emailCherch' => 'required|email|max:100|unique:chercheurs,emailCherch',
            'dateNaissCherch' => 'nullable|date',
            'dateArriveeCherch' => 'nullable|date',
            'telCherch' => 'nullable|string|max:30',
            'idUMRI' => 'nullable|exists:umris,idUMRI',
            'grades' => 'required|array',
            'grades.*' => 'exists:grades,idGrade',
            'gradeDates' => 'nullable|array',
            'gradeDates.*' => 'nullable|date'
        ]);

        DB::beginTransaction();

        try {
            // Créer le chercheur
            $chercheur = Chercheur::create([
                'nomCherch' => $validated['nomCherch'],
                'prenomCherch' => $validated['prenomCherch'],
                'genreCherch' => $validated['genreCherch'],
                'matriculeCherch' => $validated['matriculeCherch'],
                'password' => Hash::make($validated['password']),
                'emploiCherch' => $validated['emploiCherch'],
                'departementCherch' => $validated['departementCherch'],
                'fonctionAdministrativeCherch' => $validated['fonctionAdministrativeCherch'],
                'specialiteCherch' => $validated['specialiteCherch'],
                'emailCherch' => $validated['emailCherch'],
                'dateNaissCherch' => $validated['dateNaissCherch'],
                'dateArriveeCherch' => $validated['dateArriveeCherch'],
                'telCherch' => $validated['telCherch'],
                'idUMRI' => $validated['idUMRI']
            ]);

            // Attacher les grades avec leurs dates d'obtention
            if (isset($validated['grades']) && !empty($validated['grades'])) {
                foreach ($validated['grades'] as $gradeId) {
                    $dateGrade = isset($validated['gradeDates'][$gradeId])
                        ? $validated['gradeDates'][$gradeId]
                        : now();

                    $chercheur->grades()->attach($gradeId, [
                        'dateGrade' => $dateGrade
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.listeChercheurs')
                ->with('success', 'Chercheur créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }
}
