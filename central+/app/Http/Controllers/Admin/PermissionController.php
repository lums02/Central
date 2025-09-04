<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    // Affiche la liste des rôles
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super admin voit tous les rôles
            $roles = Role::all();
        } else {
            // Admin d'entité voit seulement les rôles pertinents pour son entité
            $roles = $this->getEntityRelevantRoles($user->type_utilisateur);
        }
        
        return view('admin.permissions.index', compact('roles'));
    }

    private function getEntityRelevantRoles($entityType)
    {
        // Rôles de base pour tous les admins d'entité
        $baseRoles = ['admin'];
        
        // Rôles spécifiques selon le type d'entité
        $entityRoles = [
            'hopital' => ['hopital_admin', 'hopital_staff', 'hopital_doctor', 'hopital_nurse'],
            'pharmacie' => ['pharmacie_admin', 'pharmacie_staff', 'pharmacie_pharmacist'],
            'banque_sang' => ['banque_admin', 'banque_staff', 'banque_technician'],
            'centre' => ['centre_admin', 'centre_staff', 'centre_doctor'],
        ];
        
        // Combiner les rôles de base avec les rôles spécifiques à l'entité
        $relevantRoleNames = array_merge($baseRoles, $entityRoles[$entityType] ?? []);
        
        // Récupérer les rôles existants qui correspondent
        return Role::whereIn('name', $relevantRoleNames)->get();
    }

    // Formulaire de création
    public function create()
    {
        return view('admin.permissions.create');
    }

    // Sauvegarde un rôle
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|unique:roles',
        ]);

        // Vérifier que l'admin d'entité ne peut créer que des rôles pour son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($request->name, $allowedPrefix)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez créer que des rôles pour votre entité (' . $allowedPrefix . '*)'
                ], 403);
            }
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        return response()->json(['success' => true, 'message' => 'Rôle créé avec succès']);
    }

    // Formulaire d’édition
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $role = Role::findOrFail($id);

        // Vérifier que l'admin d'entité ne peut modifier que les rôles de son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($role->name, $allowedPrefix) && $role->name !== 'admin') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez modifier que les rôles de votre entité'
                ], 403);
            }
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        // Mettre à jour le nom du rôle
        $role->update(['name' => $request->name]);

        // Traiter les permissions si elles sont envoyées
        if ($request->has('permissions')) {
            $permissions = json_decode($request->permissions, true);
            
            // Créer les permissions si elles n'existent pas
            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
            
            // Synchroniser les permissions avec le rôle
            $role->syncPermissions($permissions);
        }

        return response()->json(['success' => true, 'message' => 'Rôle mis à jour avec succès']);
    }

    // Suppression
    public function destroy($id)
    {
        $user = auth()->user();
        $role = Role::findOrFail($id);
        
        // Vérifier que l'admin d'entité ne peut supprimer que les rôles de son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($role->name, $allowedPrefix) && $role->name !== 'admin') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez supprimer que les rôles de votre entité'
                ], 403);
            }
        }
        
        // Vérifier si le rôle est utilisé par des utilisateurs
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Impossible de supprimer ce rôle car il est attribué à des utilisateurs'
            ]);
        }
        
        // Supprimer le rôle et ses permissions
        $role->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Rôle supprimé avec succès'
        ]);
    }
    public function show(Permission $permission)
    {
        return view('admin.permissions.show', compact('permission'));
    }

    // Récupérer les permissions d'un rôle
    public function getRolePermissions($id)
    {
        $user = auth()->user();
        $role = Role::findOrFail($id);

        // Vérifier que l'admin d'entité ne peut voir que les rôles de son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($role->name, $allowedPrefix) && $role->name !== 'admin') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez voir que les rôles de votre entité'
                ], 403);
            }
        }

        // Récupérer toutes les permissions disponibles (filtrées selon l'entité)
        if ($user->isSuperAdmin()) {
            $allPermissions = Permission::all();
        } else {
            $allPermissions = $this->getEntityRelevantPermissions($user->type_utilisateur);
        }

        // Récupérer les permissions actuelles du rôle
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return response()->json([
            'success' => true,
            'permissions' => $allPermissions,
            'role_permissions' => $rolePermissions
        ]);
    }

    // Méthode pour filtrer les permissions selon l'entité
    private function getEntityRelevantPermissions($entityType)
    {
        $permissionGroups = [
            'hopital' => [
                'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
                'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments',
                'view_medical_records', 'create_medical_records', 'edit_medical_records', 'delete_medical_records',
                'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions',
                'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_consultations', 'create_consultations', 'edit_consultations', 'delete_consultations'
            ],
            'pharmacie' => [
                'view_medicines', 'create_medicines', 'edit_medicines', 'delete_medicines',
                'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks',
                'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions',
                'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles'
            ],
            'banque_sang' => [
                'view_donors', 'create_donors', 'edit_donors', 'delete_donors',
                'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles'
            ],
            'centre' => [
                'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
                'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments',
                'view_consultations', 'create_consultations', 'edit_consultations', 'delete_consultations',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles'
            ]
        ];

        $relevantPermissions = $permissionGroups[$entityType] ?? [];
        
        return Permission::whereIn('name', $relevantPermissions)->get();
    }
}
