<?php

namespace App\Http\Controllers;

use App\Models\ExamenPrescrit;
use App\Models\Utilisateur;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaissierController extends Controller
{
    /**
     * Dashboard du caissier
     */
    public function dashboard()
    {
        $caissier = Auth::user();
        
        $stats = [
            'examens_en_attente' => ExamenPrescrit::where('hopital_id', $caissier->entite_id)
                ->where('statut_paiement', 'en_attente')->count(),
            'paiements_aujourd_hui' => ExamenPrescrit::where('hopital_id', $caissier->entite_id)
                ->where('statut_paiement', 'paye')
                ->whereDate('date_paiement', today())->count(),
            'total_aujourd_hui' => ExamenPrescrit::where('hopital_id', $caissier->entite_id)
                ->where('statut_paiement', 'paye')
                ->whereDate('date_paiement', today())->sum('prix'),
            'total_mois' => ExamenPrescrit::where('hopital_id', $caissier->entite_id)
                ->where('statut_paiement', 'paye')
                ->whereMonth('date_paiement', now()->month)->sum('prix'),
        ];
        
        return view('caissier.dashboard', compact('stats'));
    }
    
    /**
     * Liste des examens en attente de paiement
     */
    public function examensEnAttente()
    {
        $caissier = Auth::user();
        
        $examens = ExamenPrescrit::where('hopital_id', $caissier->entite_id)
            ->where('statut_paiement', 'en_attente')
            ->with(['patient', 'medecin', 'dossierMedical'])
            ->orderBy('date_prescription', 'desc')
            ->paginate(20);
        
        return view('caissier.examens', compact('examens'));
    }
    
    /**
     * Valider le paiement d'un examen
     */
    public function validerPaiement(Request $request, $id)
    {
        $caissier = Auth::user();
        
        $request->validate([
            'prix' => 'required|numeric|min:0',
        ]);
        
        $examen = ExamenPrescrit::where('id', $id)
            ->where('hopital_id', $caissier->entite_id)
            ->firstOrFail();
        
        $examen->update([
            'prix' => $request->prix,
            'statut_paiement' => 'paye',
            'date_paiement' => now(),
            'valide_par' => $caissier->id,
            'statut_examen' => 'paye',
        ]);
        
        // Notifier le laborantin
        $laborantins = Utilisateur::where('type_utilisateur', 'hopital')
            ->where('entite_id', $caissier->entite_id)
            ->where('role', 'laborantin')
            ->get();
        
        foreach ($laborantins as $laborantin) {
            Notification::create([
                'user_id' => $laborantin->id,
                'hopital_id' => null,
                'type' => 'examen_a_realiser',
                'title' => 'Nouvel examen à réaliser',
                'message' => 'Examen payé : ' . $examen->nom_examen . ' pour ' . $examen->patient->nom,
                'data' => json_encode(['examen_id' => $examen->id]),
                'read' => false,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Paiement validé. Le laborantin a été notifié.'
        ]);
    }
}

