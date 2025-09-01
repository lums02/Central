<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'utilisateurs'; // Nom de la table

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'type_utilisateur',
        'entite_id',
        'status',
        'rejection_reason',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    public $timestamps = true; // Activé pour la compatibilité avec Spatie

    /**
     * Pour que Laravel sache que le mot de passe est "mot_de_passe"
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * Relation avec la table hôpitaux
     */
    public function entite()
    {
        // Exemple avec relation classique
        return $this->belongsTo(Hopital::class, 'entite_id');
    }
    

    /**
     * Exemple de scopes (optionnel mais pratique)
     */
    public function scopeHopital($query)
    {
        return $query->where('role', 'hopital');
    }

    public function scopePharmacie($query)
    {
        return $query->where('role', 'pharmacie');
    }

    public function scopePatient($query)
    {
        return $query->where('role', 'patient');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur a un type spécifique
     */
    public function hasType($type)
    {
        return $this->type_utilisateur === $type;
    }

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Vérifier si l'utilisateur est en attente d'approbation
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si l'utilisateur est approuvé
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Vérifier si l'utilisateur est rejeté
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approuver l'utilisateur
     */
    public function approve()
    {
        $this->update(['status' => self::STATUS_APPROVED]);
    }

    /**
     * Rejeter l'utilisateur
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Vérifier si l'utilisateur est un superadmin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin' || $this->email === 'admin@central.com';
    }

    /**
     * Vérifier si l'utilisateur peut être supprimé
     */
    public function canBeDeleted()
    {
        return !$this->isSuperAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut être modifié
     */
    public function canBeModified()
    {
        // Le superadmin peut être modifié mais pas supprimé
        return true;
    }

    /**
     * Attribuer toutes les permissions au superadmin
     */
    public function assignAllPermissions()
    {
        if ($this->isSuperAdmin()) {
            $allPermissions = \Spatie\Permission\Models\Permission::all();
            $this->syncPermissions($allPermissions);
            
            // S'assurer que le rôle superadmin existe
            $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'superadmin',
                'guard_name' => 'web'
            ]);
            
            $this->assignRole($superAdminRole);
        }
    }

    /**
     * Boot method pour automatiquement attribuer toutes les permissions au superadmin
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($utilisateur) {
            if ($utilisateur->isSuperAdmin()) {
                $utilisateur->assignAllPermissions();
            }
        });

        static::updated(function ($utilisateur) {
            if ($utilisateur->isSuperAdmin()) {
                $utilisateur->assignAllPermissions();
            }
        });
    }

    /**
     * Vérifier si l'utilisateur connecté est le superadmin
     */
    public static function isCurrentUserSuperAdmin()
    {
        return auth()->check() && auth()->user()->isSuperAdmin();
    }

    /**
     * Obtenir le superadmin
     */
    public static function getSuperAdmin()
    {
        return static::where('email', 'admin@central.com')->first();
    }

    /**
     * Vérifier si l'utilisateur est le premier de son type d'entité
     */
    public function isFirstOfEntityType()
    {
        return !static::where('type_utilisateur', $this->type_utilisateur)
            ->where('status', 'approved')
            ->where('id', '!=', $this->id)
            ->exists();
    }

    /**
     * Vérifier si l'utilisateur peut être promu admin
     */
    public function canBePromotedToAdmin()
    {
        // Seulement si c'est le premier de son type d'entité
        return $this->isFirstOfEntityType();
    }
}
