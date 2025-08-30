@extends('layouts.admin')

@section('title', 'Utilisateurs en Attente - CENTRAL+')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Utilisateurs en Attente d'Approbation</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn" style="background: white; color: #003366; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;">
                            <i class="fas fa-users me-2"></i>Voir Tous les Utilisateurs
                        </a>
                        <button class="btn" style="background: #28a745; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;" onclick="refreshPendingUsers()">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-primary mb-1" id="pendingCount">0</h3>
                            <p class="text-muted mb-0">En attente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-success mb-1" id="approvedCount">0</h3>
                            <p class="text-muted mb-0">Approuvés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-danger mb-1" id="rejectedCount">0</h3>
                            <p class="text-muted mb-0">Rejetés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-info mb-1" id="totalCount">0</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des utilisateurs en attente -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">#</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Nom</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Email</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Type</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Inscrit le</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingUsersTable">
                                <!-- Les utilisateurs en attente seront chargés ici -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour rejeter un utilisateur -->
<div class="modal fade" id="rejectUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #dc3545; color: white;">
                <h5 class="modal-title">Rejeter l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectUserForm">
                    @csrf
                    <input type="hidden" id="rejectUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Raison du rejet (optionnel)</label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" placeholder="Expliquez pourquoi cet utilisateur est rejeté..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" onclick="confirmRejectUser()">Rejeter</button>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    font-weight: 600;
    color: #003366;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.8rem;
}

/* Boutons d'action stylisés */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    cursor: pointer;
}

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #20c997, #17a2b8);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.btn-view {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.btn-view:hover {
    background: linear-gradient(135deg, #138496, #117a8b);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
}

/* Animation au survol */
.btn-icon:hover {
    transform: translateY(-2px);
}

.btn-icon:focus {
    outline: 2px solid #003366;
    outline-offset: 2px;
}

/* Animation d'apparition */
.btn-icon {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive pour petits écrans */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>

<script>
let currentRejectUserId = null;

// Charger les utilisateurs en attente au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadPendingUsers();
    loadUserStats();
});

// Charger les utilisateurs en attente
function loadPendingUsers() {
    fetch('{{ route("admin.users.pending") }}')
        .then(response => response.json())
        .then(data => {
            displayPendingUsers(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

// Afficher les utilisateurs en attente
function displayPendingUsers(users) {
    const tbody = document.getElementById('pendingUsersTable');
    tbody.innerHTML = '';

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4" style="color: #6c757d;">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Aucun utilisateur en attente d'approbation
                </td>
            </tr>
        `;
        return;
    }

    users.forEach((user, index) => {
        const row = document.createElement('tr');
        row.style.borderBottom = '1px solid #e9ecef';
        
        row.innerHTML = `
            <td style="padding: 1rem; vertical-align: middle;">${index + 1}</td>
            <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">${user.nom}</td>
            <td style="padding: 1rem; vertical-align: middle;">${user.email}</td>
            <td style="padding: 1rem; vertical-align: middle;">
                <span class="badge" style="background: #17a2b8; color: white; padding: 0.5rem 0.75rem; border-radius: 4px;">
                    ${user.type_utilisateur.charAt(0).toUpperCase() + user.type_utilisateur.slice(1)}
                </span>
            </td>
            <td style="padding: 1rem; vertical-align: middle; color: #6c757d;">
                ${new Date(user.created_at).toLocaleDateString('fr-FR')}
            </td>
            <td style="padding: 1rem; vertical-align: middle;">
                <div class="action-buttons">
                    <button class="btn btn-icon btn-view" onclick="viewUserDetails(${user.id})" title="Voir les détails">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-icon btn-approve" onclick="approveUser(${user.id})" title="Approuver l'utilisateur">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-icon btn-reject" onclick="rejectUser(${user.id})" title="Rejeter l'utilisateur">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Approuver un utilisateur
function approveUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir approuver cet utilisateur ?')) {
        fetch(`{{ route("admin.users.approve", ":id") }}`.replace(':id', userId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Utilisateur approuvé avec succès !');
                loadPendingUsers();
                loadUserStats();
            } else {
                alert('Erreur lors de l\'approbation : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'approbation de l\'utilisateur');
        });
    }
}

// Rejeter un utilisateur
function rejectUser(userId) {
    currentRejectUserId = userId;
    const modal = new bootstrap.Modal(document.getElementById('rejectUserModal'));
    modal.show();
}

// Confirmer le rejet d'un utilisateur
function confirmRejectUser() {
    if (!currentRejectUserId) return;

    const form = document.getElementById('rejectUserForm');
    const formData = new FormData(form);
    
    fetch(`{{ route("admin.users.reject", ":id") }}`.replace(':id', currentRejectUserId), {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Utilisateur rejeté avec succès !');
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('rejectUserModal'));
            modal.hide();
            // Recharger les données
            loadPendingUsers();
            loadUserStats();
            // Réinitialiser le formulaire
            form.reset();
            currentRejectUserId = null;
        } else {
            alert('Erreur lors du rejet : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du rejet de l\'utilisateur');
    });
}

// Voir les détails d'un utilisateur
function viewUserDetails(userId) {
    // Rediriger vers la page de gestion des utilisateurs
    window.location.href = `{{ route("admin.users.index") }}?user=${userId}`;
}

// Charger les statistiques des utilisateurs
function loadUserStats() {
    fetch('{{ route("admin.users.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalCount').textContent = data.total;
            
            // Compter les utilisateurs par statut
            const pendingCount = data.par_status.find(item => item.status === 'pending')?.total || 0;
            const approvedCount = data.par_status.find(item => item.status === 'approved')?.total || 0;
            const rejectedCount = data.par_status.find(item => item.status === 'rejected')?.total || 0;
            
            document.getElementById('pendingCount').textContent = pendingCount;
            document.getElementById('approvedCount').textContent = approvedCount;
            document.getElementById('rejectedCount').textContent = rejectedCount;
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

// Actualiser la liste des utilisateurs en attente
function refreshPendingUsers() {
    loadPendingUsers();
    loadUserStats();
}

// Réinitialiser le formulaire quand le modal se ferme
document.getElementById('rejectUserModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('rejectUserForm').reset();
    currentRejectUserId = null;
});
</script>
@endsection
