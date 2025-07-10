<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs'; // Nom de la table

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',         // rôle : hopital, pharmacie, patient, etc.
        'entite_id',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token', // si tu veux activer la session persistante
    ];

    public $timestamps = false;

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
}
