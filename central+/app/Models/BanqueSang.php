<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BanqueSang extends Model
{
    protected $table = 'banque_sangs';

    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'logo',
    ];
}
