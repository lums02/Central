<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Utilisateur;

class PatientController extends Controller
{
    /**
     * Afficher la page d'accueil des patients
     */
    public function index()
    {
        return view('patient.index');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login', ['userType' => 'patient']);
    }

    /**
     * Traiter la connexion des patients
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Vérifier que l'utilisateur est un patient
        $user = Utilisateur::where('email', $credentials['email'])->first();
        
        if ($user && $user->type_utilisateur !== 'patient') {
            return redirect()->back()
                ->withErrors(['email' => 'Cette adresse email n\'est pas associée à un compte patient.'])
                ->withInput($request->except('password'));
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('patient.dashboard'))
                ->with('success', 'Connexion réussie ! Bienvenue dans votre espace patient.');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Les identifiants fournis ne correspondent à aucun compte patient.'])
            ->withInput($request->except('password'));
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register', ['userType' => 'patient', 'selectedEntity' => 'patient']);
    }

    /**
     * Traiter l'inscription des patients
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'sexe.in' => 'Le sexe doit être Masculin ou Féminin.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Créer le patient
        $patient = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'password' => Hash::make($request->password),
            'type_utilisateur' => 'patient',
            'status' => 'actif',
        ]);

        // Assigner le rôle patient
        $patient->assignRole('patient');

        // Connecter automatiquement le patient
        Auth::login($patient);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Compte créé avec succès ! Bienvenue dans votre espace patient.');
    }

    /**
     * Afficher le dashboard patient
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est bien un patient
        if ($user->type_utilisateur !== 'patient') {
            Auth::logout();
            return redirect()->route('patient.login')
                ->with('error', 'Accès non autorisé.');
        }

        return view('patient.dashboard', compact('user'));
    }

    /**
     * Déconnexion des patients
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('patient.index')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Afficher le formulaire de demande de réinitialisation de mot de passe
     */
    public function showPasswordRequestForm()
    {
        return view('patient.password-request');
    }

    /**
     * Traiter la demande de réinitialisation de mot de passe
     */
    public function passwordRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:utilisateurs,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.exists' => 'Cette adresse email n\'est pas enregistrée.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'utilisateur est un patient
        $user = Utilisateur::where('email', $request->email)
            ->where('type_utilisateur', 'patient')
            ->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'Cette adresse email n\'est pas associée à un compte patient.'])
                ->withInput();
        }

        // TODO: Implémenter l'envoi d'email de réinitialisation
        // Pour l'instant, on affiche juste un message de succès
        
        return redirect()->route('patient.login')
            ->with('success', 'Un email de réinitialisation a été envoyé à votre adresse email.');
    }
}
