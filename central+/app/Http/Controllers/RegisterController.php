<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 // adapte selon ton modèle utilisateur
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function submit(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:hopitaux,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'type_hopital' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Upload logo si présent
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            // Création de l'hôpital
            $hopital = Hopital::create([
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'adresse' => $validated['adresse'],
                'type_hopital' => $validated['type_hopital'],
                'nombre_lits' => 200,  // par défaut ou à modifier
                'logo' => $logoPath,
            ]);

            // Création de l'utilisateur admin associé
            Utilisateur::create([
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'mot_de_passe' => Hash::make($validated['password']),
                'role' => 'admin',
                'hopital_id' => $hopital->id,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Inscription réussie, connectez-vous.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erreur lors de l\'inscription : ' . $e->getMessage()])->withInput();
        }
    }
}



