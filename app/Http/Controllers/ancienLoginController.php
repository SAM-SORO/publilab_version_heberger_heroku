<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Chercheur;
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
        } elseif (Auth::guard('chercheur')->attempt(['emailCherch' => $request->email, 'password' => $request->password])) {
            Auth::guard('chercheur')->login($user);
            return redirect()->route('chercheur.espace');
        } elseif (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::guard('admin')->login($user);
            return redirect()->route('admin.espace');
        } else {
            // Si aucune authentification n'a réussi, rediriger vers la page de login avec une erreur
            session()->flash('error', "Erreur lors de l'authentification après l'inscription");
            return redirect()->route('login');
        }
    }



    public function login_submit(Request $request) {
        // Validation des données
        $request->validate([
            'email_visit' => 'required|email',
            'password_visit' => 'required',
        ]);


        $credentiels = [
            'email' => $request->input('email_visit'),
            'password' => $request->input('password_visit'),
        ];


        if (Auth::guard('visiteur')->attempt($credentiels)) {
            $user = Visiteur::where('email', $request->input('email_visit'))->first();
            Auth::guard("visiteur")->login($user);
            return redirect()->route('home');

        } else if (Auth::guard('chercheur')->attempt($credentiels)) {

            $user = Chercheur::where('emailCherch', $request->input('email_visit'))->first();
            Auth::guard("chercheur")->login($user);
            return redirect()->route('chercheur.espace');

        } else if (Auth::guard('admin')->attempt($credentiels)) {

            $user = Admin::where('email', $request->input('email_visit'))->first();
            Auth::guard("admin")->login($user);
            return redirect()->route('admin.espace');
        } else {

            session()->flash("error", "Adresse email ou mot de passe incorrect");
            return redirect()->route('login');
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


/*


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email_visit' => 'required|email',
            'password_visit' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
Et ensuite, tu pourrais l'utiliser dans ton contrôleur comme ceci :

php
Copier le code
public function login_submit(LoginRequest $request) {
    // Ton code d'authentification ici
}
Cela te perme


*/
