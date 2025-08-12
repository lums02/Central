<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Page d'accueil
Route::get('/', function () {
    return view('home');
});

// Enregistrement (Inscription)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');

// Connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 🔐 Espace Hôpital
Route::middleware(['auth', 'check.type:hopital'])->group(function () {
    Route::get('/hopital/dashboard', function () {
        return view('hopital.dashboard');
    })->name('hopital.dashboard');
});

// 🔐 Espace Pharmacie
Route::middleware(['auth', 'check.type:pharmacie'])->group(function () {
    Route::get('/pharmacie/dashboard', function () {
        return view('pharmacie.dashboard');
    })->name('pharmacie.dashboard');
});

// 🔐 Espace Banque de Sang
Route::middleware(['auth', 'check.type:banque_sang'])->group(function () {
    Route::get('/banque/dashboard', function () {
        return view('banque.dashboard');
    })->name('banque.dashboard');
});

// 🔐 Espace Centre Médical
Route::middleware(['auth', 'check.type:centre'])->group(function () {
    Route::get('/centre/dashboard', function () {
        return view('centre.dashboard');
    })->name('centre.dashboard');
});

// 🔐 Espace Patient
Route::middleware(['auth', 'check.type:patient'])->group(function () {
    Route::get('/patient/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.dashboard');
});
