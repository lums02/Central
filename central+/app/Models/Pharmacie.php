<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    // Laravel utilisera la table 'pharmacies' automatiquement
    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'logo'
    ];
}
