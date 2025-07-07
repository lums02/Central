<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
use App\Http\Controllers\RegisterController;

// Affiche le formulaire d'inscription
Route::get('/register', [RegisterController::class, 'create'])->name('register');

// Traite le formulaire d'inscription
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

