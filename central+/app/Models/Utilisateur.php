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
}
