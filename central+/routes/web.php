<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;

// Page d'accueil
Route::get('/', function () {
    return view('home');
});

// Enregistrement (Inscription)
// Routes d'inscription avec adaptation automatique
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::get('/register/{entity}', [RegisterController::class, 'showRegistrationForm'])->name('register.entity');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');

// Pages d'accueil des entités (exemples)
Route::get('/hopital', function() {
    return view('entities.hopital.home');
})->name('entity.hopital');

// Connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirection admin vers login si pas connecté
Route::get('/admin', function () {
    return redirect()->route('login');
})->name('admin.redirect');



// 🔐 Dashboards protégés
Route::middleware(['auth'])->group(function () {
    Route::get('/hopital/dashboard', [DashboardController::class, 'hopitalDashboard'])->name('hopital.dashboard');
    Route::get('/pharmacie/dashboard', [DashboardController::class, 'pharmacieDashboard'])->name('pharmacie.dashboard');
    Route::get('/banque/dashboard', [DashboardController::class, 'banqueSangDashboard'])->name('banque.dashboard');
    Route::get('/centre/dashboard', [DashboardController::class, 'centreDashboard'])->name('centre.dashboard');
    Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
});

// Gestion des permissions
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Route principale admin - redirige vers le dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Page d'accueil admin
    Route::get('/index', function () {
        return view('admin.index');
    })->name('index');
    
    Route::resource('permissions', PermissionController::class);
    
    // Gestion des utilisateurs - Routes spécifiques en premier
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/pending', [UserController::class, 'pendingUsers'])->name('users.pending');
    Route::get('/users/stats', [UserController::class, 'stats'])->name('users.stats');
    Route::post('/users/permissions', [UserController::class, 'updatePermissions'])->name('users.updatePermissions');
    
    // Routes avec paramètres en dernier
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('superadmin.protection');
    Route::get('/users/{id}/permissions', [UserController::class, 'showPermissions'])->name('users.permissions');
    Route::post('/users/{id}/approve', [UserController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{id}/reject', [UserController::class, 'rejectUser'])->name('users.reject');
    
    // Route en français pour la compatibilité
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/en-attente', [UserController::class, 'pendingUsers'])->name('utilisateurs.pending');
    
    // Gestion des entités
    Route::get('/entities', function () {
        return view('admin.entities');
    })->name('entities');
    
    // Gestion des settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});