<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Valider les champs reçus
        $credentials = $request->validate([
            'email'            => ['required', 'email'],
            'mot_de_passe'     => ['required'],
            'type_utilisateur' => ['required'], // entité
        ]);

        // On récupère l'utilisateur par email + type_utilisateur
        $utilisateur = Utilisateur::where('email', $request->email)
                                  ->where('type_utilisateur', $request->type_utilisateur)
                                  ->first();

        // Vérification du mot de passe
        if ($utilisateur && Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            Auth::login($utilisateur);

            // Rediriger selon le type_utilisateur (entité)
            switch ($utilisateur->type_utilisateur) {
                case 'hopital':
                    return redirect()->intended('/hopital/dashboard');
                case 'pharmacie':
                    return redirect()->intended('/pharmacie/dashboard');
                case 'banque_sang':
                    return redirect()->intended('/banque/dashboard');
                case 'centre':
                    return redirect()->intended('/centre/dashboard');
                case 'patient':
                    return redirect()->intended('/patient/dashboard');
                default:
                    return redirect()->intended('/dashboard');
            }
        }

        // Si les identifiants sont incorrects
        return back()->withErrors([
            'email' => 'Email, mot de passe ou entité incorrect.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
