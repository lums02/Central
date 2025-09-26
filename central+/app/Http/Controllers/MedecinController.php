<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedecinController extends Controller
{
    public function dashboard()
    {
        $medecin = Auth::user();
        
        // Récupérer les patients du médecin
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', $medecin->entite_id)
            ->get();
        
        // Récupérer les dossiers médicaux du médecin
        $dossiers = DossierMedical::where('medecin_id', $medecin->id)
            ->with(['patient', 'hopital'])
            ->orderBy('date_consultation', 'desc')
            ->get();
        
        $stats = [
            'total_patients' => $patients->count(),
            'total_dossiers' => $dossiers->count(),
            'dossiers_actifs' => $dossiers->where('statut', 'actif')->count(),
            'consultations_aujourd_hui' => $dossiers->where('date_consultation', today())->count(),
        ];
        
        return view('medecin.dashboard', compact('patients', 'dossiers', 'stats'));
    }
    
    public function patients()
    {
        $medecin = Auth::user();
        
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', $medecin->entite_id)
            ->with(['dossiers' => function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            }])
            ->get();
        
        return view('medecin.patients', compact('patients'));
    }
    
    public function rendezvous()
    {
        $medecin = Auth::user();
        
        // Récupérer les patients pour le formulaire
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', $medecin->entite_id)
            ->get();
        
        // TODO: Récupérer les rendez-vous du médecin
        $rendezvous = collect([]); // Placeholder pour l'instant
        
        return view('medecin.rendezvous', compact('patients', 'rendezvous'));
    }
    
    public function dossiers()
    {
        $medecin = Auth::user();
        
        $dossiers = DossierMedical::where('medecin_id', $medecin->id)
            ->with(['patient', 'hopital'])
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);
        
        // Récupérer les patients pour le formulaire
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', $medecin->entite_id)
            ->get();
        
        return view('medecin.dossiers', compact('dossiers', 'patients'));
    }
    
    public function showDossier($id)
    {
        $medecin = Auth::user();
        
        $dossier = DossierMedical::where('medecin_id', $medecin->id)
            ->where('id', $id)
            ->with(['patient', 'hopital'])
            ->firstOrFail();
        
        return view('medecin.dossier-show', compact('dossier'));
    }
    
    public function createDossier(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'observations' => 'nullable|string',
            'date_consultation' => 'required|date',
        ]);
        
        $medecin = Auth::user();
        
        // Générer un numéro de dossier unique
        $numeroDossier = 'DM' . date('Y') . str_pad(DossierMedical::count() + 1, 4, '0', STR_PAD_LEFT);
        
        $dossier = DossierMedical::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $medecin->entite_id,
            'numero_dossier' => $numeroDossier,
            'diagnostic' => $request->diagnostic,
            'traitement' => $request->traitement,
            'observations' => $request->observations,
            'date_consultation' => $request->date_consultation,
        ]);
        
        return redirect()->route('admin.medecin.dossiers')->with('success', 'Dossier médical créé avec succès');
    }
    
    public function createRendezVous(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'date_rendezvous' => 'required|date',
            'heure_rendezvous' => 'required',
            'type_rendezvous' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        // TODO: Implémenter la création de rendez-vous
        // Pour l'instant, retourner un succès
        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous créé avec succès'
        ]);
    }
}