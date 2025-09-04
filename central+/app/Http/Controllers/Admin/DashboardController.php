<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Déterminer le type de dashboard selon le rôle et le type d'utilisateur
        if ($user->isSuperAdmin()) {
            // Dashboard Super Admin - Statistiques globales
            $data = $this->getSuperAdminStats();
        } elseif ($user->role === 'admin') {
            // Dashboard Admin d'entité - Statistiques spécifiques à l'entité
            $data = $this->getEntityAdminStats($user);
        } else {
            // Dashboard utilisateur normal
            $data = $this->getUserStats($user);
        }

        // Permissions de l'utilisateur pour l'affichage
        $data['user_permissions'] = $user->getAllPermissions()->pluck('name')->toArray();
        $data['user_role'] = $user->role;
        $data['user_type'] = $user->type_utilisateur;

        return view('admin.dashboard', compact('data'));
    }

    private function getSuperAdminStats()
    {
        return [
            'dashboard_type' => 'superadmin',
            'total_users' => Utilisateur::count(),
            'pending_users' => Utilisateur::where('status', 'pending')->count(),
            'approved_users' => Utilisateur::where('status', 'approved')->count(),
            'total_roles' => \Spatie\Permission\Models\Role::count(),
            'entity_stats' => [
                'hopitaux' => Utilisateur::where('type_utilisateur', 'hopital')->count(),
                'pharmacies' => Utilisateur::where('type_utilisateur', 'pharmacie')->count(),
                'banques_sang' => Utilisateur::where('type_utilisateur', 'banque_sang')->count(),
                'centres' => Utilisateur::where('type_utilisateur', 'centre')->count(),
            ]
        ];
    }

    private function getEntityAdminStats($user)
    {
        $stats = [
            'dashboard_type' => 'entity_admin',
            'entity_type' => $user->type_utilisateur,
            'entity_name' => ucfirst(str_replace('_', ' ', $user->type_utilisateur)),
        ];

        // Statistiques selon le type d'entité et les permissions
        switch ($user->type_utilisateur) {
            case 'hopital':
                $stats['total_patients'] = 0; // Patient::where('hopital_id', $user->hopital_id)->count();
                $stats['total_appointments'] = 0; // Appointment::where('hopital_id', $user->hopital_id)->count();
                $stats['today_appointments'] = 0; // Appointment::where('hopital_id', $user->hopital_id)->whereDate('date', today())->count();
                $stats['total_consultations'] = 0; // Consultation::where('hopital_id', $user->hopital_id)->count();
                $stats['total_prescriptions'] = 0; // Prescription::where('hopital_id', $user->hopital_id)->count();
                break;

            case 'pharmacie':
                $stats['total_medicines'] = 0; // Medicine::where('pharmacie_id', $user->pharmacie_id)->count();
                $stats['low_stock_items'] = 0; // Stock::where('pharmacie_id', $user->pharmacie_id)->where('quantity', '<', 'min_quantity')->count();
                $stats['total_orders'] = 0; // Order::where('pharmacie_id', $user->pharmacie_id)->count();
                $stats['pending_orders'] = 0; // Order::where('pharmacie_id', $user->pharmacie_id)->where('status', 'pending')->count();
                break;

            case 'banque_sang':
                $stats['total_donors'] = 0; // Donor::where('banque_sang_id', $user->banque_sang_id)->count();
                $stats['blood_reserves'] = [
                    'A+' => 0, // BloodReserve::where('banque_sang_id', $user->banque_sang_id)->where('blood_type', 'A+')->sum('quantity');
                    'A-' => 0,
                    'B+' => 0,
                    'B-' => 0,
                    'AB+' => 0,
                    'AB-' => 0,
                    'O+' => 0,
                    'O-' => 0,
                ];
                $stats['recent_donations'] = 0; // Donation::where('banque_sang_id', $user->banque_sang_id)->where('created_at', '>=', now()->subDays(7))->count();
                break;

            case 'centre':
                $stats['total_patients'] = 0; // Patient::where('centre_id', $user->centre_id)->count();
                $stats['total_consultations'] = 0; // Consultation::where('centre_id', $user->centre_id)->count();
                $stats['today_consultations'] = 0; // Consultation::where('centre_id', $user->centre_id)->whereDate('date', today())->count();
                $stats['total_prescriptions'] = 0; // Prescription::where('centre_id', $user->centre_id)->count();
                break;
        }

        return $stats;
    }

    private function getUserStats($user)
    {
        return [
            'dashboard_type' => 'user',
            'user_type' => $user->type_utilisateur,
            'welcome_message' => 'Bienvenue dans votre espace personnel',
        ];
    }
}
