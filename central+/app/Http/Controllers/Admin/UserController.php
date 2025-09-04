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
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super admin voit tous les utilisateurs approuvés
            $utilisateurs = Utilisateur::where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Admin d'entité voit seulement les utilisateurs de son entité
            $utilisateurs = Utilisateur::where('status', 'approved')
                ->where('type_utilisateur', $user->type_utilisateur)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('admin.users', compact('utilisateurs'));
    }

    // Affiche un utilisateur spécifique
    public function show($id)
    {
        $user = auth()->user();
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'admin d'entité ne peut voir que les utilisateurs de son entité
        if (!$user->isSuperAdmin() && $utilisateur->type_utilisateur !== $user->type_utilisateur) {
            return response()->json([
                'success' => false, 
                'message' => 'Accès non autorisé à cet utilisateur'
            ], 403);
        }
        
        return response()->json($utilisateur);
    }

    // Met à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'admin d'entité ne peut modifier que les utilisateurs de son entité
        if (!$user->isSuperAdmin() && $utilisateur->type_utilisateur !== $user->type_utilisateur) {
            return response()->json([
                'success' => false, 
                'message' => 'Vous ne pouvez modifier que les utilisateurs de votre entité'
            ], 403);
        }
        
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
        try {
            $user = auth()->user();
            $utilisateur = Utilisateur::findOrFail($id);
            
            // Vérifier que l'admin d'entité ne peut supprimer que les utilisateurs de son entité
            if (!$user->isSuperAdmin() && $utilisateur->type_utilisateur !== $user->type_utilisateur) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez supprimer que les utilisateurs de votre entité'
                ], 403);
            }
            
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

            // Supprimer les permissions et rôles avant de supprimer l'utilisateur
            $utilisateur->syncPermissions([]);
            $utilisateur->syncRoles([]);
            
            $utilisateur->delete();
            
            return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression d\'utilisateur: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    // Affiche les permissions d'un utilisateur
    public function showPermissions($id)
    {
        $user = auth()->user();
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'admin d'entité ne peut voir que les utilisateurs de son entité
        if (!$user->isSuperAdmin() && $utilisateur->type_utilisateur !== $user->type_utilisateur) {
            return response()->json([
                'success' => false, 
                'message' => 'Accès non autorisé à cet utilisateur'
            ], 403);
        }
        
        // Filtrer les permissions selon l'entité de l'utilisateur connecté
        if ($user->isSuperAdmin()) {
            // Super admin voit toutes les permissions
            $permissions = Permission::all();
        } else {
            // Admin d'entité voit seulement les permissions pertinentes pour son entité
            $permissions = $this->getEntityRelevantPermissions($user->type_utilisateur);
        }
        
        // Récupérer les permissions de l'utilisateur (directes + via les rôles)
        $userPermissions = $utilisateur->getAllPermissions()->pluck('name')->toArray();

        return response()->json([
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
            'user' => [
                'id' => $utilisateur->id,
                'nom' => $utilisateur->nom,
                'email' => $utilisateur->email,
                'role' => $utilisateur->role,
                'type_utilisateur' => $utilisateur->type_utilisateur,
                'status' => $utilisateur->status
            ]
        ]);
    }

    private function getEntityRelevantPermissions($entityType)
    {
        // Permissions de base pour tous les admins d'entité
        $basePermissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
        ];
        
        // Permissions spécifiques selon le type d'entité
        $entityPermissions = [];
        switch ($entityType) {
            case 'hopital':
                $entityPermissions = [
                    'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
                    'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments',
                    'view_medical_records', 'create_medical_records', 'edit_medical_records', 'delete_medical_records',
                    'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions',
                    'view_consultations', 'create_consultations', 'edit_consultations', 'delete_consultations',
                    'view_services', 'create_services', 'edit_services', 'delete_services',
                    'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
                    'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                ];
                break;
                
            case 'pharmacie':
                $entityPermissions = [
                    'view_medicines', 'create_medicines', 'edit_medicines', 'delete_medicines',
                    'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks',
                    'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
                    'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                ];
                break;
                
            case 'banque_sang':
                $entityPermissions = [
                    'view_donors', 'create_donors', 'edit_donors', 'delete_donors',
                    'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves', 'delete_blood_reserves',
                    'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                ];
                break;
                
            case 'centre':
                $entityPermissions = [
                    'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
                    'view_consultations', 'create_consultations', 'edit_consultations', 'delete_consultations',
                    'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions',
                    'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                ];
                break;
        }
        
        // Combiner les permissions de base avec les permissions spécifiques à l'entité
        $relevantPermissionNames = array_merge($basePermissions, $entityPermissions);
        
        // Récupérer les permissions existantes qui correspondent
        return Permission::whereIn('name', $relevantPermissionNames)->get();
    }

    // Met à jour les permissions d'un utilisateur
    public function updatePermissions(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'user_id' => 'required|exists:utilisateurs,id',
            'permissions' => 'nullable|string', // Peut être JSON string
            'role' => 'required|string|in:user,admin,manager,moderator,superadmin',
            'type_utilisateur' => 'required|string|in:hopital,pharmacie,banque_sang,centre,patient,admin',
        ]);

        $utilisateur = Utilisateur::findOrFail($request->user_id);
        
        // Vérifier que l'admin d'entité ne peut modifier que les utilisateurs de son entité
        if (!$user->isSuperAdmin() && $utilisateur->type_utilisateur !== $user->type_utilisateur) {
            return response()->json([
                'success' => false, 
                'message' => 'Vous ne pouvez modifier que les utilisateurs de votre entité'
            ], 403);
        }
        
        // Récupérer les permissions
        $permissions = $request->input('permissions', []);
        
        // Si les permissions sont envoyées en JSON, les décoder
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?? [];
        }
        
        // Vérifier que l'admin d'entité ne peut attribuer que des permissions pertinentes pour son entité
        if (!$user->isSuperAdmin() && !empty($permissions)) {
            $allowedPermissions = $this->getEntityRelevantPermissions($user->type_utilisateur)->pluck('name')->toArray();
            $unauthorizedPermissions = array_diff($permissions, $allowedPermissions);
            
            if (!empty($unauthorizedPermissions)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez attribuer que des permissions pertinentes pour votre entité'
                ], 403);
            }
        }
        
        // Mettre à jour le rôle et le type
        $utilisateur->update([
            'role' => $request->role,
            'type_utilisateur' => $request->type_utilisateur,
        ]);

        // Attribuer le rôle approprié
        $roleModel = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => $request->role,
            'guard_name' => 'web'
        ]);
        $utilisateur->assignRole($roleModel);

        // Si c'est un superadmin, lui attribuer automatiquement toutes les permissions
        if ($request->role === 'superadmin') {
            $utilisateur->assignAllPermissions();
        } else {
            // Mettre à jour les permissions pour les autres utilisateurs
            if (!empty($permissions)) {
                // Convertir les noms de permissions en objets Permission
                $permissionObjects = [];
                foreach ($permissions as $permissionName) {
                    $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
                        'name' => $permissionName,
                        'guard_name' => 'web'
                    ]);
                    $permissionObjects[] = $permission;
                }
                $utilisateur->syncPermissions($permissionObjects);
            } else {
                $utilisateur->syncPermissions([]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Permissions mises à jour avec succès']);
    }

    // Approuve un utilisateur en attente
    public function approveUser(Request $request, $id)
    {
        $user = auth()->user();
        $utilisateur = Utilisateur::findOrFail($id);
        
        // Vérifier que l'utilisateur est en attente
        if ($utilisateur->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur n\'est pas en attente d\'approbation'
            ]);
        }

        // Vérifier que l'admin d'entité ne peut approuver que les utilisateurs de son entité
        if (!$user->isSuperAdmin() && $utilisateur->type_utilisateur !== $user->type_utilisateur) {
            return response()->json([
                'success' => false, 
                'message' => 'Vous ne pouvez approuver que les utilisateurs de votre entité'
            ], 403);
        }

        // Récupérer les données du formulaire
        $role = $request->input('role', 'user');
        $typeUtilisateur = $request->input('type_utilisateur', $utilisateur->type_utilisateur);
        $permissions = $request->input('permissions', []);

        // Si les permissions sont envoyées en JSON, les décoder
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?? [];
        }

        // Vérifier que l'admin d'entité ne peut attribuer que des permissions pertinentes pour son entité
        if (!$user->isSuperAdmin() && !empty($permissions)) {
            $allowedPermissions = $this->getEntityRelevantPermissions($user->type_utilisateur)->pluck('name')->toArray();
            $unauthorizedPermissions = array_diff($permissions, $allowedPermissions);
            
            if (!empty($unauthorizedPermissions)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez attribuer que des permissions pertinentes pour votre entité'
                ], 403);
            }
        }

        // Mettre à jour le rôle et le type d'utilisateur
        $utilisateur->update([
            'status' => 'approved',
            'role' => $role,
            'type_utilisateur' => $typeUtilisateur
        ]);

        // Attribuer le rôle approprié
        $roleModel = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => $role,
            'guard_name' => 'web'
        ]);
        $utilisateur->assignRole($roleModel);

        // Gérer les permissions
        if ($role === 'superadmin') {
            // Le superadmin a automatiquement toutes les permissions
            $utilisateur->assignAllPermissions();
        } else {
            // Attribuer les permissions spécifiées
            if (!empty($permissions)) {
                // Convertir les noms de permissions en objets Permission
                $permissionObjects = [];
                foreach ($permissions as $permissionName) {
                    $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
                        'name' => $permissionName,
                        'guard_name' => 'web'
                    ]);
                    $permissionObjects[] = $permission;
                }
                $utilisateur->syncPermissions($permissionObjects);
            } else {
                // Si aucune permission n'est spécifiée, utiliser la logique automatique
                if ($utilisateur->isFirstOfEntityType()) {
                    $this->assignEntityPermissions($utilisateur, $typeUtilisateur);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur approuvé avec succès et permissions attribuées'
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
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super admin voit tous les utilisateurs en attente
            $pendingUsers = Utilisateur::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Admin d'entité voit seulement les utilisateurs en attente de son entité
            $pendingUsers = Utilisateur::where('status', 'pending')
                ->where('type_utilisateur', $user->type_utilisateur)
                ->orderBy('created_at', 'desc')
                ->get();
        }
            
        // Debug: Log pour voir ce qui se passe
        \Log::info('Pending users request', [
            'is_ajax' => request()->ajax(),
            'wants_json' => request()->wantsJson(),
            'accept' => request()->header('Accept'),
            'count' => $pendingUsers->count(),
            'auth_check' => auth()->check(),
            'user_type' => $user->type_utilisateur,
            'is_superadmin' => $user->isSuperAdmin()
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
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super admin voit toutes les statistiques
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
        } else {
            // Admin d'entité voit seulement les statistiques de son entité
            $entityType = $user->type_utilisateur;
            $stats = [
                'total' => Utilisateur::where('type_utilisateur', $entityType)->count(),
                'pending' => Utilisateur::where('status', 'pending')->where('type_utilisateur', $entityType)->count(),
                'approved' => Utilisateur::where('status', 'approved')->where('type_utilisateur', $entityType)->count(),
                'rejected' => Utilisateur::where('status', 'rejected')->where('type_utilisateur', $entityType)->count(),
                'par_status' => Utilisateur::where('type_utilisateur', $entityType)
                    ->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get(),
                'par_type' => Utilisateur::where('type_utilisateur', $entityType)
                    ->select('type_utilisateur', DB::raw('count(*) as total'))
                    ->groupBy('type_utilisateur')
                    ->get(),
                'par_role' => Utilisateur::where('type_utilisateur', $entityType)
                    ->select('role', DB::raw('count(*) as total'))
                    ->groupBy('role')
                    ->get(),
                'recent' => Utilisateur::where('type_utilisateur', $entityType)
                    ->where('created_at', '>=', now()->subDays(7))->count(),
            ];
        }

        return response()->json($stats);
    }


}
