@extends('layouts.admin')

@section('title', 'Tableau de Bord Admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête du tableau de bord -->
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 600;">
            <i class="fas fa-tachometer-alt me-3" style="color: #00a8e8;"></i>Tableau de Bord Administrateur
        </h1>
        <p style="color: #b3d9ff; margin: 0.5rem 0 0 0; font-size: 1.1rem;">
            Gestion centralisée du système Central+
        </p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #003366;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" id="totalUsers" style="color: #003366; font-weight: 600;">-</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Utilisateurs Totaux</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #003366 0%, #002244 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #ffc107;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" id="pendingUsers" style="color: #ffc107; font-weight: 600;">-</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">En Attente</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #28a745;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" id="approvedUsers" style="color: #28a745; font-weight: 600;">-</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Approuvés</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #00a8e8;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0" id="totalRoles" style="color: #00a8e8; font-weight: 600;">-</h3>
                        <p class="mb-0" style="color: #666; font-size: 0.9rem;">Rôles Créés</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #00a8e8 0%, #007bff 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-shield fa-2x text-white"></i>
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
                        <a href="{{ route('admin.users.pending') }}" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-user-clock fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Gérer les Utilisateurs en Attente</strong>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.permissions.index') }}" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-user-shield fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Gérer les Rôles et Permissions</strong>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-users fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Gérer les Utilisateurs</strong>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.entities') }}" class="action-btn" style="display: block; background: #003366; color: white; text-decoration: none; padding: 1.5rem; border-radius: 15px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-building fa-2x mb-2 d-block" style="color: white;"></i>
                            <strong>Gérer les Entités</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activité récente et statistiques -->
    <div class="row">
        <div class="col-md-6">
            <div class="activity-section" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h5 class="mb-3" style="color: #003366; font-weight: 600;">
                    <i class="fas fa-history me-2" style="color: #00a8e8;"></i>Activité Récente
                </h5>
                <div class="activity-list">
                    <div class="activity-item" style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid #eee;">
                        <div style="background: #003366; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="color: #333; font-weight: 600;">Nouvel utilisateur en attente</h6>
                            <small style="color: #666;">Il y a 2 heures</small>
                        </div>
                    </div>
                    
                    <div class="activity-item" style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid #eee;">
                        <div style="background: #003366; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="color: #333; font-weight: 600;">Utilisateur approuvé</h6>
                            <small style="color: #666;">Il y a 4 heures</small>
                        </div>
                    </div>
                    
                    <div class="activity-item" style="display: flex; align-items: center; padding: 1rem 0;">
                        <div style="background: #003366; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="color: #333; font-weight: 600;">Nouveau rôle créé</h6>
                            <small style="color: #666;">Il y a 1 jour</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="stats-section" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h5 class="mb-3" style="color: #003366; font-weight: 600;">
                    <i class="fas fa-chart-pie me-2" style="color: #28a745;"></i>Statistiques par Type
                </h5>
                <div id="typeStats">
                    <!-- Les statistiques seront chargées ici -->
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

.activity-item:hover {
    background: rgba(0, 51, 102, 0.05);
    border-radius: 8px;
    padding: 1rem;
    margin: 0 -1rem;
}
</style>

<script>
// Charger les statistiques au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
});

function loadDashboardStats() {
    // Charger les statistiques des utilisateurs
    fetch('{{ route("admin.users.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalUsers').textContent = data.total;
            
            // Compter par statut
            const pendingCount = data.par_status.find(item => item.status === 'pending')?.total || 0;
            const approvedCount = data.par_status.find(item => item.status === 'approved')?.total || 0;
            
            document.getElementById('pendingUsers').textContent = pendingCount;
            document.getElementById('approvedUsers').textContent = approvedCount;
            
            // Afficher les statistiques par type
            displayTypeStats(data.par_type);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function displayTypeStats(typeStats) {
    const container = document.getElementById('typeStats');
    let html = '';
    
    typeStats.forEach(stat => {
        const icon = getTypeIcon(stat.type_utilisateur);
        const color = getTypeColor(stat.type_utilisateur);
        
        html += `
            <div class="d-flex justify-content-between align-items-center mb-3 p-2" style="border-radius: 8px; transition: background 0.3s ease;">
                <div class="d-flex align-items-center">
                    <i class="fas ${icon} me-2" style="color: ${color};"></i>
                    <span class="text-capitalize" style="color: #333; font-weight: 500;">${stat.type_utilisateur.replace('_', ' ')}</span>
                </div>
                <span class="badge" style="background: ${color}; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">${stat.total}</span>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function getTypeIcon(type) {
    const icons = {
        'hopital': 'fa-hospital',
        'pharmacie': 'fa-pills',
        'banque_sang': 'fa-tint',
        'centre': 'fa-clinic-medical',
        'patient': 'fa-user-injured',
        'admin': 'fa-user-shield'
    };
    return icons[type] || 'fa-user';
}

function getTypeColor(type) {
    const colors = {
        'hopital': '#003366',
        'pharmacie': '#003366',
        'banque_sang': '#003366',
        'centre': '#003366',
        'patient': '#003366',
        'admin': '#003366'
    };
    return colors[type] || '#003366';
}
</script>
@endsection
