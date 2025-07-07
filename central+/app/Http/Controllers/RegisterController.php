<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hopital;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Afficher le formulaire d'inscription
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Traiter le formulaire d'inscription
    public function register(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:hopitaux,email',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:500',
            'type_hopital' => 'required|string',
            'logo' => 'nullable|string', // on attend une chaîne base64 optionnelle
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Traitement du logo base64 si fourni
        $logo_path = null;
        if ($request->has('logo') && $request->logo) {
            $logoData = $request->logo;
            // Supprimer le préfixe base64 si présent
            $logoData = preg_replace('#^data:image/\w+;base64,#i', '', $logoData);
            $logoData = base64_decode($logoData);

            $logoName = uniqid('hospital_') . '.png';
            $uploadPath = public_path('uploads/logos/');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            file_put_contents($uploadPath . $logoName, $logoData);

            $logo_path = 'uploads/logos/' . $logoName;
        }

        // Création de l'hôpital
        $hopital = Hopital::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'type_hopital' => $request->type_hopital,
            'nombre_lits' => 200, // par défaut
            'logo' => $logo_path,
        ]);

        // Création de l'utilisateur admin associé
        $user = User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'role' => 'admin',
            'hopital_id' => $hopital->id,
        ]);

        // Connexion automatique (optionnel) ou redirection
        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Inscription réussie, vous pouvez vous connecter.');
    }
}
