<?php

namespace App\Http\Controllers;

use App\Models\Chercheur;
use App\Models\Visiteur;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // Valider les données du formulaire de login
        $credentials = $request->validate([
            'email_visit' => 'required|email',
            'password_visit' => 'required',
        ], [
            'email_visit.required' => 'L\'adresse email est obligatoire.',
            'email_visit.email' => 'L\'adresse email doit être valide.',
            'password_visit.required' => 'Le mot de passe est obligatoire.',
        ]);


        // Tenter d'authentifier l'utilisateur
        // if (Auth::guard('visiteur_veb')->attempt(['email_visit' => $credentials['email_visit'], 'password_visit' =>  $credentials['password_visit'] ])) {
        //     return redirect()->intended(RouteServiceProvider::HOME);
        // } else {
        //     return back()->withErrors([
        //         'email' => 'Les informations de connexion fournies ne correspondent pas à nos enregistrements.',
        //     ]);
        // }
        
        $isChercheur = Chercheur::where('email_ch', $credentials['email_visit'])->where('password_ch', $credentials['password_visit']);
        $isVisiteur = Visiteur::where('email_visit', $credentials['email_visit'])->where('password_visit', $credentials['password_visit'])->exists();

        if($isChercheur->count() > 0 ){
            //session()->flash("chercheur" , "true" );
            session()->start();
            return back();
        }else{

            session()->start();
            return redirect()->route('home');
        }
        //silueCaleb@gmail.com

    }
}
