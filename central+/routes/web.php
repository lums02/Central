<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Page d'accueil
Route::get('/', function () {
    return view('home');
});


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
