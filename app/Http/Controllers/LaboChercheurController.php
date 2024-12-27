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
            'idLabo' => 'required|exists:laboratoires,idLabo',
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
            'idLabo.exists' => 'Le laboratoire sélectionné n\'existe pas.',

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
        // Validation des données envoyées par le formulaire
        $validated = $request->validate([
            'chercheurId' => 'required|exists:chercheurs,idCherch',
            'grades.*.sigleGrade' => 'nullable|string|max:255',
            'grades.*.nomGrade' => 'required|string|max:255',
            'grades.*.dateGrade' => 'nullable|date',
        ], [
            'grades.*.sigleGrade.max' => 'Le sigle du grade ne peut pas dépasser 255 caractères.',
            'grades.*.nomGrade.required' => 'Le nom du grade est obligatoire.',
            'grades.*.dateGrade.date' => 'La date d\'attribution doit être une date valide.',
        ]);

        // Utiliser une transaction pour l'ajout des grades
        DB::beginTransaction();

        try {
            // Récupérer le chercheur
            $chercheur = Chercheur::find($request->chercheurId);

            if (!$chercheur) {
                return redirect()->back()->with('error', 'Chercheur non trouvé.');
            }

            // Ajouter les grades explicitement dans la table de liaison
            foreach ($request->grades as $gradeData) {
                // Trouver ou créer le grade avec sigle et nom
                $grade = Grade::firstOrCreate([
                    'sigleGrade' => $gradeData['sigleGrade'],
                    'nomGrade' => $gradeData['nomGrade']
                ]);


                // Si la date est fournie, l'utiliser, sinon la laisser null
                $dateGrade = $gradeData['dateGrade'] ?: null;

                // Enregistrer explicitement dans la table de liaison chercheur_grade
                DB::table('chercheur_grade')->insert([
                    'idCherch' => $chercheur->idCherch, // ID du chercheur
                    'idGrade' => $grade->idGrade, // ID du grade, ici c'est idGrade
                    'dateGrade' => $dateGrade, // Date d'attribution
                ]);

                // // Ajouter le grade au chercheur dans la table de liaison
                // $chercheur->grades()->attach($grade->id, [
                //     'dateGrade' => $dateGrade,
                // ]);
            }

            // Valider la transaction
            DB::commit();

            // Retourner une réponse avec succès
            return redirect()->route('admin.listeChercheurs', $chercheur->idCherch)
                            ->with('success', 'Grade(s) ajouté(s) avec succès');
        } catch (\Exception $e) {
            // En cas d'erreur, annuler toutes les modifications
            DB::rollBack();

            // Retourner une réponse avec l'erreur
            return redirect()->route('admin.listeChercheurs', $chercheur->idCherch)
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
            'idLabo' => 'required|exists:laboratoires,idLabo', // Vérifie si le laboratoire existe
            'password' => 'nullable|min:8|confirmed', // Mot de passe facultatif
        ], [
            'nomCherch.required' => 'Le nom du chercheur est obligatoire.',
            'nomCherch.max' => 'Le nom du chercheur ne peut pas dépasser 255 caractères.',

            'prenomCherch.max' => 'Le prénom du chercheur ne peut pas dépasser 255 caractères.',

            'adresse.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',

            'telCherch.regex' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',

            'emailCherch.required' => 'L\'email du chercheur est obligatoire.',
            'emailCherch.email' => 'Veuillez entrer une adresse email valide.',
            'emailCherch.unique' => 'Cette adresse email est déjà utilisée par un autre chercheur.',

            'specialite.max' => 'La spécialité ne peut pas dépasser 255 caractères.',

            'idLabo.required' => 'Le laboratoire est obligatoire.',
            'idLabo.exists' => 'Le laboratoire sélectionné est invalide.',

            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Recherche du chercheur à modifier
        $chercheur = Chercheur::findOrFail($idCherch);

        // Mise à jour des données
        $chercheur->nomCherch = $validated['nomCherch'];
        $chercheur->prenomCherch = $validated['prenomCherch'] ?? $chercheur->prenomCherch;
        $chercheur->adresse = $validated['adresse'] ?? $chercheur->adresse;
        $chercheur->telCherch = $validated['telCherch'] ?? $chercheur->telCherch;
        $chercheur->emailCherch = $validated['emailCherch'];
        $chercheur->specialite = $validated['specialite'] ?? $chercheur->specialite;
        $chercheur->idLabo = $validated['idLabo'];

        // Mise à jour du mot de passe uniquement si un nouveau mot de passe est fourni
        if (!empty($validated['password'])) {
            $chercheur->password = bcrypt($validated['password']); // Hachage du mot de passe
        }

        // Sauvegarde des modifications
        $chercheur->save();

        // Redirection avec un message de succès
        return redirect()->route('admin.listeChercheurs')->with('success', 'Le chercheur a été mis à jour avec succès.');
    }



    public function search(Request $request)
    {
        // Récupérer la requête de recherche
        $query = $request->input('query');

        // Effectuer la recherche dans la table 'chercheurs'
        $chercheurs = Chercheur::query()
            ->when($query, function ($queryBuilder) use ($query) {
                // Recherche sur les champs 'nomCherch', 'prenomCherch', et 'nomLabo'
                $queryBuilder->where('nomCherch', 'like', '%' . $query . '%')
                             ->where('prenomCherch', 'like', '%' . $query . '%')
                             ->orWhere('prenomCherch', 'like', '%' . $query . '%')
                             ->orWhereHas('laboratoire', function ($queryBuilder) use ($query) {
                                 $queryBuilder->where('nomLabo', 'like', '%' . $query . '%');
                             });
            })
            ->paginate(10);  // Pagination des résultats

        $grades = Grade::all();  // Récupérer tous les grades disponibles
        $laboratoires = Laboratoire::all();  // Récupérer tous les laboratoires disponibles

        // Retourner la vue avec les résultats
        return view('lab.admin.liste_chercheur', compact('chercheurs', 'query','grades', 'laboratoires'));
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
