<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Chercheur;
use App\Models\Doctorant;
use App\Models\Document;
use App\Models\User;
use App\Models\Visiteur;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function login(){
        return view('lab.auth.login');
    }

    public function register(){
        return view('lab.auth.register');
    }


    public function login_submit(Request $request) {
        // Validation des données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $credentiels = [
            'password' => $request->input('password'),
        ];

        // Essaye de trouver l'utilisateur dans chaque type
        $guards = [
            'admin' => 'email',                // Champ pour Admin
            'chercheur' => 'emailCherch',     // Champ pour Chercheur
            'doctorant' => 'emailDoc',            // Champ pour Doctorant
        ];

        foreach ($guards as $guard => $emailField) {
            // Ajoute le champ email approprié
            $credentiels[$emailField] = $request->input('email');

            if (Auth::guard($guard)->attempt($credentiels)) {
                // Trouver l'utilisateur
                $user = null;
                switch ($guard) {
                    case 'doctorant':
                        $user = Doctorant::where('emailDoc', $request->input('email'))->first();
                        break;
                    case 'chercheur':
                        $user = Chercheur::where('emailCherch', $request->input('email'))->first();
                        break;
                    case 'admin':
                        $user = Admin::where('email', $request->input('email'))->first();
                        break;
                }

                // Vérifie si l'utilisateur a été trouvé avant de se connecter
                if ($user) {
                    Auth::guard($guard)->login($user);

                    //redirection selon le type
                    if ($guard === 'chercheur') {
                        return redirect()->route('chercheur.espace');
                    } elseif ($guard === 'admin') {
                        return redirect()->route('admin.espace');
                    }if ($guard === 'doctorant') {
                        return redirect()->route('doctorant.espace');
                    }
                }
            }

            // Retire le champ email pour la prochaine itération
            unset($credentiels[$emailField]);
        }

        // Si aucun utilisateur n'a été trouvé
        session()->flash("error", "Adresse email ou mot de passe incorrect");
        return redirect()->route('login')->withInput();

    }


    public function register_submit(Request $request)
    {
        // Validation des données
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Création de l'utilisateur
        $user = Visiteur::create([
            'nom' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Authentification de l'utilisateur après l'inscription
        if (Auth::guard('visiteur')->attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::guard('visiteur')->login($user);
            return redirect()->route('home');
        }

    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }




    public function existEmail() {
        $email = null;
        $user = User::where('email', $email)->first();
        $response = $user ? "exist" : "not_exist";

        return response()->json([
            'code' => 200,
            'response' => $response,
        ]);
    }

}
