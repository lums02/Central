<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function hopitalDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'hopital') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('hopital.dashboard');
    }

    public function pharmacieDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'pharmacie') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('pharmacie.dashboard');
    }

    public function banqueSangDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'banque_sang') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('banque.dashboard');
    }

    public function centreDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'centre') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('centre.dashboard');
    }

    public function patientDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'patient') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('patient.dashboard');
    }
}