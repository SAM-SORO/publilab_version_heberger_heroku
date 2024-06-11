<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Chercheur;
use App\Models\User;
use App\Models\Visiteur;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function login(){
        return view('lab.auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function login_submit(Request $request) {
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

            $user = Chercheur::where('email', $request->input('email_visit'))->first();
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
