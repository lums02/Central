<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

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

// 🔐 Dashboards protégés
Route::middleware(['auth'])->group(function () {
    Route::get('/hopital/dashboard', [DashboardController::class, 'hopitalDashboard'])->name('hopital.dashboard');
    Route::get('/pharmacie/dashboard', [DashboardController::class, 'pharmacieDashboard'])->name('pharmacie.dashboard');
    Route::get('/banque/dashboard', [DashboardController::class, 'banqueSangDashboard'])->name('banque.dashboard');
    Route::get('/centre/dashboard', [DashboardController::class, 'centreDashboard'])->name('centre.dashboard');
    Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
});