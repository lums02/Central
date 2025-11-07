<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\DossierMedical;
use App\Models\RendezVous;
use App\Models\ExamenPrescrit;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedecinController extends Controller
{
    public function dashboard()
    {
        $medecin = Auth::user();
        
        // Récupérer UNIQUEMENT les patients de SON hôpital
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', $medecin->entite_id)
            ->get();
        
        // Récupérer UNIQUEMENT ses dossiers médicaux
        $dossiers = DossierMedical::where('medecin_id', $medecin->id)
            ->where('hopital_id', $medecin->entite_id)
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
        
        // Récupérer les rendez-vous du médecin
        $rendezvous = RendezVous::where('medecin_id', $medecin->id)
            ->with(['patient', 'hopital'])
            ->orderBy('date_rendezvous', 'desc')
            ->orderBy('heure_rendezvous', 'desc')
            ->get();
        
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
        $numeroDossier = 'DM-' . date('Ymd') . '-' . str_pad(DossierMedical::count() + 1, 5, '0', STR_PAD_LEFT);
        
        $dossier = DossierMedical::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $medecin->entite_id,
            'numero_dossier' => $numeroDossier,
            'motif_consultation' => $request->motif_consultation ?? 'Consultation',
            'diagnostic' => $request->diagnostic,
            'traitement' => $request->traitement,
            'observations' => $request->observations,
            'date_consultation' => $request->date_consultation,
            'statut' => 'actif',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Dossier médical créé avec succès',
            'dossier' => $dossier
        ]);
    }
    
    public function createRendezVous(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'date_rendezvous' => 'required|date|after_or_equal:today',
            'heure_rendezvous' => 'required',
            'type_rendezvous' => 'required|string',
            'motif' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $medecin = Auth::user();
        
        RendezVous::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $medecin->entite_id,
            'date_rendezvous' => $request->date_rendezvous,
            'heure_rendezvous' => $request->heure_rendezvous,
            'type_consultation' => $request->type_rendezvous,
            'motif' => $request->motif,
            'notes' => $request->notes,
            'statut' => 'en_attente',
            'prix' => 0,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous créé avec succès'
        ]);
    }
    
    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateRendezVousStatut(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $rendezvous = RendezVous::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->firstOrFail();
        
        $rendezvous->update(['statut' => $request->statut]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour'
        ]);
    }
    
    /**
     * Mettre à jour un dossier médical
     */
    public function updateDossier(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $dossier = DossierMedical::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->firstOrFail();
        
        $dossier->update([
            'diagnostic' => $request->diagnostic,
            'traitement' => $request->traitement,
            'observations' => $request->observations,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Dossier mis à jour'
        ]);
    }
    
    /**
     * Prescrire des examens médicaux
     */
    public function prescrireExamens(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $dossier = DossierMedical::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->firstOrFail();
        
        $examens = $request->examens;
        $examensCreated = [];
        
        foreach ($examens as $examenData) {
            $numeroExamen = 'EX-' . date('Ymd') . '-' . str_pad(ExamenPrescrit::count() + 1, 5, '0', STR_PAD_LEFT);
            
            $examen = ExamenPrescrit::create([
                'dossier_medical_id' => $dossier->id,
                'patient_id' => $dossier->patient_id,
                'medecin_id' => $medecin->id,
                'hopital_id' => $medecin->entite_id,
                'numero_examen' => $numeroExamen,
                'type_examen' => $examenData['type_examen'],
                'nom_examen' => $examenData['nom_examen'],
                'indication' => $examenData['indication'],
                'date_prescription' => $examenData['date_prescription'] ?? now(),
                'prix' => 0, // Le caissier fixera le prix
                'statut_paiement' => 'en_attente',
                'statut_examen' => 'prescrit',
            ]);
            
            $examensCreated[] = $examen;
        }
        
        // Créer notification pour le caissier
        $caissiers = Utilisateur::where('type_utilisateur', 'hopital')
            ->where('entite_id', $medecin->entite_id)
            ->where('role', 'caissier')
            ->get();
        
        foreach ($caissiers as $caissier) {
            Notification::create([
                'user_id' => $caissier->id,
                'hopital_id' => null,
                'type' => 'examens_a_payer',
                'title' => 'Examens à valider',
                'message' => 'Le Dr. ' . $medecin->nom . ' a prescrit ' . count($examensCreated) . ' examen(s) pour ' . $dossier->patient->nom,
                'data' => json_encode(['dossier_id' => $dossier->id, 'examens' => array_column($examensCreated, 'id')]),
                'read' => false,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Examens prescrits avec succès'
        ]);
    }
}