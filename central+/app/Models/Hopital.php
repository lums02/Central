<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hopital extends Model
{
    protected $table = 'hopitaux'; // Nom réel de la table dans ta base

    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'telephone',
        'type_hopital',
        'nombre_lits',
        'logo',
    ];

    public $timestamps = false; // Si tu n'as pas created_at / updated_at
}
