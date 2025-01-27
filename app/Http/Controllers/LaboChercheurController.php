<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chercheur;
use App\Models\Grade;
use App\Models\Laboratoire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class LaboChercheurController extends Controller
{

    public function index()
    {
        // Récupérer les chercheurs avec leurs grades et leur laboratoire associés, triés par date de création en ordre décroissant
        $chercheurs = Chercheur::with('grades', 'laboratoire')
            ->orderByDesc('created_at') // Tri par date de création (vous pouvez changer ce champ si nécessaire)
            ->paginate(10); // Pagination avec 10 résultats par page

        // Récupérer tous les grades disponibles
        $grades = Grade::all();

        // Récupérer tous les laboratoires disponibles
        $laboratoires = Laboratoire::all();

        // Retourner la vue avec les chercheurs, grades et laboratoires
        return view('lab.admin.liste_chercheur', compact('chercheurs', 'grades', 'laboratoires'));
    }



    public function create(Request $request)
    {
        // Validation des données avec messages personnalisés
        $validated = $request->validate([
            'nomCherch' => 'required|string|max:255',
            'prenomCherch' => 'required|string|max:255',
            'emailCherch' => 'required|email|unique:chercheurs,emailCherch',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'idLabo' => 'required',
            'dateArrivee' => 'nullable|date',
            'adresse' => 'nullable|string|max:255',
            'telCherch' => 'nullable|regex:/^[0-9]{10,}$/',
            'specialite' => 'nullable|string|max:255',
        ], [
            'nomCherch.required' => 'Le nom est obligatoire.',
            'nomCherch.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'prenomCherch.required' => 'Le prénom est obligatoire.',
            'prenomCherch.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'emailCherch.required' => 'L\'adresse email est obligatoire.',
            'emailCherch.email' => 'L\'adresse email doit être valide.',
            'emailCherch.unique' => 'Cette adresse email est déjà utilisée.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',

            'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
            'password_confirmation.same' => 'La confirmation du mot de passe ne correspond pas.',

            'idLabo.required' => 'Le laboratoire est obligatoire.',

            'telCherch.required' => 'Le numéro de téléphone est obligatoire.',
            'telCherch.regex' => 'Le numéro de téléphone doit comporter au moins 10 chiffres.',

            'dateArrivee.date' => 'La date d\'arrivée doit être une date valide.',
            'adresse.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',
            'specialite.max' => 'La spécialité ne doit pas dépasser 255 caractères.',
        ]);

        // Hachage du mot de passe
        $password = Hash::make($validated['password']);

        // Création du chercheur avec les informations de base
        $chercheur = new Chercheur();
        $chercheur->nomCherch = $validated['nomCherch'];
        $chercheur->prenomCherch = $validated['prenomCherch'];
        $chercheur->emailCherch = $validated['emailCherch'];
        $chercheur->password = $password;
        $chercheur->idLabo = $validated['idLabo'];
        $chercheur->adresse = $validated['adresse'];
        $chercheur->telCherch = $validated['telCherch'];
        $chercheur->specialite = $validated['specialite'];
        $chercheur->dateArrivee = $validated['dateArrivee'];

        // Sauvegarde du chercheur
        $chercheur->save();

        // Redirection avec un message de succès
        return redirect()->route('admin.listeChercheurs')->with('success', 'Chercheur ajouté avec succès.');
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


    public function edit($id)
    {
        try {
            // Récupérer le chercheur avec ses relations
            $chercheur = Chercheur::with(['grades', 'laboratoire'])->findOrFail($id);

            // Récupérer tous les grades et laboratoires pour les listes déroulantes
            $grades = Grade::all();
            $laboratoires = Laboratoire::all();

            // Retourner la vue avec les données
            return view('lab.admin.modifier_chercheur', compact('chercheur', 'grades', 'laboratoires'));

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->route('admin.listeChercheurs')
                            ->with('error', 'Une erreur est survenue lors de la récupération des données du chercheur.');
        }
    }

    public function update(Request $request, $idCherch)
    {
        // Validation des données avec messages personnalisés
        $validated = $request->validate([
            'nomCherch' => 'required|string|max:255',
            'prenomCherch' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'telCherch' => 'nullable|regex:/^[0-9]{10}$/', // 10 chiffres pour un numéro de téléphone
            'emailCherch' => 'required|email|max:255|unique:chercheurs,emailCherch,' . $idCherch . ',idCherch',
            'specialite' => 'nullable|string|max:255',
            'idLabo' => 'nullable',
            'password' => 'nullable|min:8|confirmed', // Mot de passe facultatif
            'grades' => 'nullable|array', // Grades facultatifs
            'dates' => 'nullable|array', // Dates facultatives
            'dates.*' => 'nullable|date',
        ], [
            'nomCherch.required' => 'Le nom du chercheur est obligatoire.',
            'emailCherch.required' => 'L\'email du chercheur est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            // Recherche du chercheur à modifier
            $chercheur = Chercheur::findOrFail($idCherch);

            // Mise à jour des données du chercheur
            $chercheur->nomCherch = $validated['nomCherch'];
            $chercheur->prenomCherch = $validated['prenomCherch'] ?? $chercheur->prenomCherch;
            $chercheur->adresse = $validated['adresse'] ?? $chercheur->adresse;
            $chercheur->telCherch = $validated['telCherch'] ?? $chercheur->telCherch;
            $chercheur->emailCherch = $validated['emailCherch'];
            $chercheur->specialite = $validated['specialite'] ?? $chercheur->specialite;

            // Mise à jour de la relation avec le laboratoire (nullable)
            $chercheur->idLabo = $validated['idLabo'] ?? null;

            // Gestion des grades et des dates d'obtention
            if (isset($validated['grades']) && is_array($validated['grades'])) {
                // Préparer les données pour `sync` avec les dates
                $gradesWithDates = [];
                foreach ($validated['grades'] as $gradeId) {
                    $gradesWithDates[$gradeId] = [
                        'dateGrade' => $validated['dates'][$gradeId] ?? null,
                    ];
                }

                // Synchroniser la relation grades avec la table pivot
                $chercheur->grades()->sync($gradesWithDates);
            } else {
                // Si aucun grade n'est fourni, désynchroniser tous les grades
                $chercheur->grades()->detach();
            }

            // Mise à jour du mot de passe uniquement si un nouveau mot de passe est fourni
            if (!empty($validated['password'])) {
                $chercheur->password = bcrypt($validated['password']); // Hachage du mot de passe
            }

            // Sauvegarde des modifications
            $chercheur->save();

            // Redirection avec un message de succès
            return redirect()->route('admin.listeChercheurs')->with('success', 'Le chercheur a été mis à jour avec succès.');

        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->route('admin.listeChercheurs')->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
        }
    }




    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = trim($request->input('query'));

        // Effectuer la recherche dans la table 'chercheurs'
        $chercheurs = Chercheur::query()
            ->when($query, function ($queryBuilder) use ($query) {
                // Diviser la requête en mots-clés (chaque mot ou groupe de mots)
                $keywords = explode(' ', $query);

                if (count($keywords) > 1) {
                    // Si plusieurs mots sont saisis, chercher par nom et prénom combinés
                    $nom = $keywords[0]; // Premier mot comme nom
                    $prenom = implode(' ', array_slice($keywords, 1)); // Le reste comme prénom

                    $queryBuilder->where('nomCherch', 'like', '%' . $nom . '%')
                                ->where('prenomCherch', 'like', '%' . $prenom . '%');
                } else {
                    // Si un seul mot est saisi, chercher par nom ou prénom
                    $queryBuilder->where('nomCherch', 'like', '%' . $query . '%')
                                ->orWhere('prenomCherch', 'like', '%' . $query . '%');
                }
            })
            ->orWhereHas('laboratoire', function ($queryBuilder) use ($query) {
                $queryBuilder->where('nomLabo', 'like', '%' . $query . '%');
            })
            ->paginate(10); // Pagination des résultats

        $grades = Grade::all(); // Récupérer tous les grades disponibles
        $laboratoires = Laboratoire::all(); // Récupérer tous les laboratoires disponibles

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_chercheur', compact('chercheurs', 'query', 'grades', 'laboratoires'));
    }




    public function delete($id)
    {
        try {
            // Trouver le chercheur à supprimer
            $chercheur = Chercheur::findOrFail($id);

            // Vérifier s'il existe des dépendances
            if ($chercheur->articles()->exists() ||
                $chercheur->doctorants()->exists()) {
                return redirect()->back()->with(
                    'error',
                    'Le chercheur ne peut pas être supprimé car il est associé à des articles ou doctorants. Veuillez dissocier ou supprimer les dépendances avant de continuer.'
                );
            }

            // Démarrer une transaction
            DB::beginTransaction();

            // Supprimer les relations many-to-many
            $chercheur->articles()->detach();
            $chercheur->doctorants()->detach();
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
}
