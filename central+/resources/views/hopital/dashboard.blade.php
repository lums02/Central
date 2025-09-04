@extends('layouts.app')

@section('title', 'Dashboard Hôpital - CENTRAL+')

@section('content')
<div class="container-fluid">
    <!-- En-tête du tableau de bord -->
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 600;">
            <i class="fas fa-hospital me-3" style="color: #00a8e8;"></i>Dashboard Hôpital
        </h1>
        <p style="color: #b3d9ff; margin: 0.5rem 0 0 0; font-size: 1.1rem;">
            Bienvenue, {{ auth()->user()->nom }} - Gestion de votre établissement
        </p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #003366;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" style="color: #003366; font-weight: 600;">0</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Patients</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #003366 0%, #002244 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-injured fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #ffc107;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" style="color: #ffc107; font-weight: 600;">0</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Rendez-vous</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #28a745;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" style="color: #28a745; font-weight: 600;">0</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Consultations</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-stethoscope fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #00a8e8;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" style="color: #00a8e8; font-weight: 600;">0</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Prescriptions</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #00a8e8 0%, #007bff 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-prescription fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="action-section" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h5 class="mb-3" style="color: #003366; font-weight: 600;">
                    <i class="fas fa-bolt me-2" style="color: #ffc107;"></i>Actions Rapides
                </h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="#" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Nouveau Patient</strong>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="#" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-calendar-plus fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Nouveau Rendez-vous</strong>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="#" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-stethoscope fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Nouvelle Consultation</strong>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="#" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-prescription fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Nouvelle Prescription</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations -->
    <div class="row">
        <div class="col-12">
            <div class="info-section" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h5 class="mb-3" style="color: #003366; font-weight: 600;">
                    <i class="fas fa-info-circle me-2" style="color: #00a8e8;"></i>Informations
                </h5>
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Bienvenue dans votre espace hôpital !</strong><br>
                    Ce dashboard vous permet de gérer vos patients, rendez-vous, consultations et prescriptions.
                    Les fonctionnalités complètes seront bientôt disponibles.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card:hover {
    transform: translateY(-5px);
}

.action-btn:hover {
    transform: translateY(-3px);
    text-decoration: none;
    color: white;
}
</style>
@endsection
