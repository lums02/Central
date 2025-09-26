<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'hopital_id',
        'numero_dossier',
        'diagnostic',
        'traitement',
        'observations',
        'date_consultation',
        'statut'
    ];
    
    protected $casts = [
        'date_consultation' => 'date',
    ];
    
    public function patient()
    {
        return $this->belongsTo(Utilisateur::class, 'patient_id');
    }
    
    public function medecin()
    {
        return $this->belongsTo(Utilisateur::class, 'medecin_id');
    }
    
    public function hopital()
    {
        return $this->belongsTo(Hopital::class, 'hopital_id');
    }
}