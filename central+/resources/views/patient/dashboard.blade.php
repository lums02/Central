@extends('layouts.patient')

@section('title', 'Mon Tableau de Bord')

@section('content')
<div class="container-fluid">
    <!-- En-tête simple -->
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 600;">
            <i class="fas fa-heartbeat me-3" style="color: #00a8e8;"></i>Mon Espace Santé
        </h1>
        <p style="color: #b3d9ff; margin: 0.5rem 0 0 0; font-size: 1.1rem;">
            Bienvenue {{ auth()->user()->nom }} - Votre espace personnel
        </p>
    </div>

    <!-- Contenu simple -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-injured fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted">Tableau de Bord Patient</h3>
                    <p class="text-muted">Votre espace personnel est en cours de développement.</p>
                    <p class="text-muted">Les fonctionnalités seront bientôt disponibles.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
