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
        ]);

        // On récupère l'utilisateur par email uniquement
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Vérification du mot de passe
        if ($utilisateur && Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            // Vérifier le statut de l'utilisateur
            if ($utilisateur->status === 'pending') {
                return back()->withErrors([
                    'email' => 'Votre compte est en attente d\'approbation par l\'administrateur.',
                ])->withInput();
            }

            if ($utilisateur->status === 'rejected') {
                $reason = $utilisateur->rejection_reason ? " Raison : {$utilisateur->rejection_reason}" : '';
                return back()->withErrors([
                    'email' => 'Votre compte a été rejeté.' . $reason,
                ])->withInput();
            }

            // Si l'utilisateur est approuvé, procéder à la connexion
            Auth::login($utilisateur);

            // Rediriger selon le type_utilisateur (entité)
            switch ($utilisateur->type_utilisateur) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
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
                    return redirect()->intended('/admin/dashboard');
            }
        }

        // Si les identifiants sont incorrects
        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
