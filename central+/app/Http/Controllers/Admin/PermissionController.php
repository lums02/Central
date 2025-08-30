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
        $roles = Role::all();
        return view('admin.permissions.index', compact('roles'));
    }

    // Formulaire de création
    public function create()
    {
        return view('admin.permissions.create');
    }

    // Sauvegarde un rôle
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
        ]);

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
        $role = Role::findOrFail($id);

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
        $role = Role::findOrFail($id);
        
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
}
