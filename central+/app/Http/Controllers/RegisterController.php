<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Hopital;
use App\Models\Utilisateur;
use App\Models\Pharmacie;
use App\Models\BanqueSang;
use App\Models\Centre;
use App\Models\Patient;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
public function submit(Request $request)
{
    
    // 1. Validation dynamique
    $rules = [
        'type_utilisateur' => 'required|in:hopital,pharmacie,banque_sang,centre,patient',
        'nom' => 'required|string|max:255',
        'email' => 'required|email|unique:utilisateurs,email',
        'password' => 'required|string|min:8|confirmed',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    if (in_array($request->type_utilisateur , ['hopital', 'pharmacie', 'banque_sang', 'centre'])) {
        $rules['adresse'] = 'required|string';
        if ($request->type_utilisateur === 'hopital') {
            $rules['type_hopital'] = 'required|string';
        }
    } elseif ($request->type_utilisateur === 'patient') {
        $rules['date_naissance'] = 'required|date';
        $rules['sexe'] = 'required|in:masculin,feminin';
    }

    $validated = $request->validate($rules);

    DB::beginTransaction();
    try {
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $entite_id = null;
        $type = $validated['type_utilisateur'];

        switch ($type) {
            case 'hopital':
                $entite = Hopital::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'type_hopital' => $validated['type_hopital'],
                    'nombre_lits' => 200,
                    'logo' => $logoPath,
                ]);
                break;

            case 'pharmacie':
                $entite = Pharmacie::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'logo' => $logoPath,
                ]);
                break;

            case 'banque_sang':
                $entite = BanqueSang::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'logo' => $logoPath,
                ]);
                break;

            case 'centre':
                $entite = Centre::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'logo' => $logoPath,
                ]);
                break;

            case 'patient':
                $entite = Patient::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'date_naissance' => $validated['date_naissance'],
                    'sexe' => $validated['sexe'],
                ]);
                break;

            default:
                throw new \Exception('Type d\'entité non reconnu');
        }

        $entite_id = $entite->id;
        
    


        Utilisateur::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'mot_de_passe' => Hash::make($validated['password']),
            'role' => 'admin',
            'type_utilisateur' => $type,
            'entite_id' => $entite_id,
        ]);

        DB::commit();

        return redirect()->route('login')->with('success', 'Inscription réussie !');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()])->withInput();
    }
}


}
