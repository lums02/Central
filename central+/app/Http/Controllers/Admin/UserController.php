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
        
        // Vérifier que l'utilisateur n'est pas le dernier admin
        if ($utilisateur->role === 'admin' && Utilisateur::where('role', 'admin')->count() <= 1) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer le dernier administrateur']);
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
            'role' => 'required|string|in:user,admin,manager,moderator',
            'type_utilisateur' => 'required|string|in:hopital,pharmacie,banque_sang,centre,patient',
        ]);

        $utilisateur = Utilisateur::findOrFail($request->user_id);
        
        // Mettre à jour le rôle et le type
        $utilisateur->update([
            'role' => $request->role,
            'type_utilisateur' => $request->type_utilisateur,
        ]);

        // Mettre à jour les permissions
        if ($request->has('permissions')) {
            $utilisateur->syncPermissions($request->permissions);
        } else {
            $utilisateur->syncPermissions([]);
        }

        return response()->json(['success' => true, 'message' => 'Permissions mises à jour avec succès']);
    }

    // Approuver un utilisateur
    public function approve($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        
        if ($utilisateur->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Cet utilisateur n\'est pas en attente d\'approbation']);
        }

        $utilisateur->approve();
        
        return response()->json(['success' => true, 'message' => 'Utilisateur approuvé avec succès']);
    }

    // Rejeter un utilisateur
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $utilisateur = Utilisateur::findOrFail($id);
        
        if ($utilisateur->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Cet utilisateur n\'est pas en attente d\'approbation']);
        }

        $utilisateur->reject($request->rejection_reason);
        
        return response()->json(['success' => false, 'message' => 'Utilisateur rejeté avec succès']);
    }

    // Obtenir les utilisateurs en attente d'approbation
    public function pendingUsers()
    {
        $pendingUsers = Utilisateur::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
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
