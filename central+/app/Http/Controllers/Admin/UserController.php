<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        // Dans Laravel 12, on utilise les middlewares dans les routes ou via des attributs
        // Le middleware 'auth' est déjà appliqué dans le groupe de routes
    }

    // Affiche la liste des utilisateurs
    public function index()
    {
        // Afficher seulement les utilisateurs approuvés
        $utilisateurs = Utilisateur::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.users', compact('utilisateurs'));
    }

    // Affiche un utilisateur spécifique
    public function show($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        return response()->json($utilisateur);
    }

    // Met à jour un utilisateur
    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id,
            'role' => 'required|string|in:user,admin,manager,moderator',
        ]);

        $utilisateur->update([
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json(['success' => true, 'message' => 'Utilisateur mis à jour avec succès']);
    }

    // Supprime un utilisateur
    public function destroy($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'utilisateur n'est pas un superadmin
        if (!$utilisateur->canBeDeleted()) {
            return response()->json([
                'success' => false, 
                'message' => 'Impossible de supprimer le superadmin'
            ]);
        }
        
        // Vérifier que l'utilisateur n'est pas le dernier admin
        if ($utilisateur->role === 'admin' && Utilisateur::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false, 
                'message' => 'Impossible de supprimer le dernier administrateur'
            ]);
        }

        $utilisateur->delete();
        return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
    }

    // Affiche les permissions d'un utilisateur
    public function showPermissions($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $permissions = Permission::all();
        $userPermissions = $utilisateur->permissions->pluck('id')->toArray();

        return response()->json([
            'permissions' => $permissions,
            'userPermissions' => $userPermissions
        ]);
    }

    // Met à jour les permissions d'un utilisateur
    public function updatePermissions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:utilisateurs,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'role' => 'required|string|in:user,admin,manager,moderator,superadmin',
            'type_utilisateur' => 'required|string|in:hopital,pharmacie,banque_sang,centre,patient',
        ]);

        $utilisateur = Utilisateur::findOrFail($request->user_id);
        
        // Mettre à jour le rôle et le type
        $utilisateur->update([
            'role' => $request->role,
            'type_utilisateur' => $request->type_utilisateur,
        ]);

        // Si c'est un superadmin, lui attribuer automatiquement toutes les permissions
        if ($utilisateur->isSuperAdmin()) {
            $utilisateur->assignAllPermissions();
        } else {
            // Mettre à jour les permissions pour les autres utilisateurs
            if ($request->has('permissions')) {
                $utilisateur->syncPermissions($request->permissions);
            } else {
                $utilisateur->syncPermissions([]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Permissions mises à jour avec succès']);
    }

    // Approuve un utilisateur en attente
    public function approveUser(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'utilisateur est en attente
        if ($utilisateur->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur n\'est pas en attente d\'approbation'
            ]);
        }

        // Approuver l'utilisateur
        $utilisateur->update([
            'status' => 'approved'
        ]);

        // Si c'est le premier utilisateur de son type d'entité, le promouvoir admin
        if ($utilisateur->isFirstOfEntityType()) {
            $utilisateur->update(['role' => 'admin']);
            
            // Attribuer le rôle admin
            $adminRole = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);
            $utilisateur->assignRole($adminRole);
            
            // Attribuer les permissions appropriées selon le type d'entité
            $this->assignEntityPermissions($utilisateur, $utilisateur->type_utilisateur);
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur approuvé avec succès'
        ]);
    }

    // Rejette un utilisateur en attente
    public function rejectUser(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'utilisateur est en attente
        if ($utilisateur->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur n\'est pas en attente d\'approbation'
            ]);
        }

        // Rejeter l'utilisateur
        $utilisateur->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur rejeté avec succès'
        ]);
    }

    // Attribue les permissions appropriées selon le type d'entité
    private function assignEntityPermissions($user, $entityType)
    {
        // Permissions de base pour tous les administrateurs
        $basePermissions = [
            'view_users', 'create_users', 'edit_users',
            'view_patients', 'create_patients', 'edit_patients',
            'view_appointments', 'create_appointments', 'edit_appointments',
            'view_medical_records', 'create_medical_records', 'edit_medical_records',
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
            'view_reports', 'create_reports'
        ];

        // Permissions spécifiques selon le type d'entité
        $entityPermissions = [];
        switch ($entityType) {
            case 'hopital':
                $entityPermissions = [
                    'view_consultations', 'create_consultations', 'edit_consultations',
                    'view_services', 'create_services', 'edit_services',
                    'view_hopital', 'edit_hopital'
                ];
                break;
            case 'pharmacie':
                $entityPermissions = [
                    'view_medicines', 'create_medicines', 'edit_medicines',
                    'view_stocks', 'create_stocks', 'edit_stocks',
                    'view_invoices', 'create_invoices', 'edit_invoices',
                    'view_pharmacie', 'edit_pharmacie'
                ];
                break;
            case 'banque_sang':
                $entityPermissions = [
                    'view_donors', 'create_donors', 'edit_donors',
                    'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves',
                    'view_banque_sang', 'edit_banque_sang'
                ];
                break;
            case 'centre':
                $entityPermissions = [
                    'view_centre', 'edit_centre',
                    'view_patients', 'create_patients', 'edit_patients'
                ];
                break;
            case 'patient':
                $entityPermissions = [
                    'view_patient', 'edit_patient',
                    'view_appointments', 'create_appointments'
                ];
                break;
        }

        // Créer et attribuer toutes les permissions
        $allPermissions = array_merge($basePermissions, $entityPermissions);
        
        foreach ($allPermissions as $permissionName) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
            $user->givePermissionTo($permission);
        }
    }

    // Obtenir les utilisateurs en attente d'approbation
    public function pendingUsers()
    {
        // Temporairement moins restrictif pour le debug
        // if (!auth()->check()) {
        //     abort(403, 'Vous devez être connecté pour accéder à cette section.');
        // }
        
        $pendingUsers = Utilisateur::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Debug: Log pour voir ce qui se passe
        \Log::info('Pending users request', [
            'is_ajax' => request()->ajax(),
            'wants_json' => request()->wantsJson(),
            'accept' => request()->header('Accept'),
            'count' => $pendingUsers->count(),
            'auth_check' => auth()->check()
        ]);
            
        // Si c'est une requête AJAX, retourner JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($pendingUsers);
        }
        
        // Sinon, retourner la vue
        return view('admin.users.pending', compact('pendingUsers'));
    }

    // Crée un nouvel utilisateur (optionnel)
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8',
            'role' => 'required|string|in:user,admin,manager,moderator',
            'type_utilisateur' => 'required|string|in:hopital,pharmacie,banque_sang,centre,patient',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role' => $request->role,
            'type_utilisateur' => $request->type_utilisateur,
        ]);

        return response()->json(['success' => true, 'message' => 'Utilisateur créé avec succès', 'user' => $utilisateur]);
    }

    // Statistiques des utilisateurs
    public function stats()
    {
        $stats = [
            'total' => Utilisateur::count(),
            'pending' => Utilisateur::where('status', 'pending')->count(),
            'approved' => Utilisateur::where('status', 'approved')->count(),
            'rejected' => Utilisateur::where('status', 'rejected')->count(),
            'par_status' => Utilisateur::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'par_type' => Utilisateur::select('type_utilisateur', DB::raw('count(*) as total'))
                ->groupBy('type_utilisateur')
                ->get(),
            'par_role' => Utilisateur::select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->get(),
            'recent' => Utilisateur::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json($stats);
    }


}
