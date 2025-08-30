@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs - CENTRAL+')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Gestion des Utilisateurs</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.pending') }}" class="btn" style="background: #ffc107; color: #000; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;">
                            <i class="fas fa-clock me-2"></i>Utilisateurs en Attente
                            <span class="badge bg-danger text-white ms-2" id="pendingBadge">0</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tableau des utilisateurs -->
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
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Rôle</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Inscrit le</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600; text-align: center; width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($utilisateurs as $utilisateur)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 1rem; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">{{ $utilisateur->nom }}</td>
                                    <td style="padding: 1rem; vertical-align: middle;">{{ $utilisateur->email }}</td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <span class="badge" style="background: #17a2b8; color: white; padding: 0.5rem 0.75rem; border-radius: 4px;">
                                            {{ ucfirst($utilisateur->type_utilisateur) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <span class="badge" style="background: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 4px;">
                                            {{ ucfirst($utilisateur->role) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; vertical-align: middle; color: #6c757d;">
                                        {{ $utilisateur->created_at ? $utilisateur->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <div class="action-buttons">
                                            <button class="btn btn-icon btn-permissions" onclick="openPermissionsModal({{ $utilisateur->id }}, '{{ $utilisateur->nom }}')" title="Gérer les permissions">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                            <button class="btn btn-icon btn-edit" onclick="openEditModal({{ $utilisateur->id }})" title="Modifier l'utilisateur">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-delete" onclick="deleteUser({{ $utilisateur->id }})" title="Supprimer l'utilisateur">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4" style="color: #6c757d;">
                                        Aucun utilisateur trouvé.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les permissions -->
<div class="modal fade" id="permissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title">Gérer les Permissions - <span id="userName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="permissionsForm">
                    @csrf
                    <input type="hidden" id="userId" name="user_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="color: #003366; margin-bottom: 1rem;">Permissions Disponibles</h6>
                            <div id="permissionsList">
                                <!-- Les permissions seront chargées ici dynamiquement -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 style="color: #003366; margin-bottom: 1rem;">Rôle de l'utilisateur</h6>
                            <select name="role" id="userRole" class="form-select mb-3">
                                <option value="user">Utilisateur</option>
                                <option value="admin">Administrateur</option>
                                <option value="manager">Manager</option>
                                <option value="moderator">Modérateur</option>
                            </select>
                            
                            <h6 style="color: #003366; margin-bottom: 1rem;">Type d'utilisateur</h6>
                            <select name="type_utilisateur" id="userType" class="form-select">
                                <option value="hopital">Hôpital</option>
                                <option value="pharmacie">Pharmacie</option>
                                <option value="banque_sang">Banque de Sang</option>
                                <option value="centre">Centre</option>
                                <option value="patient">Patient</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="saveUserPermissions()">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier l'utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="editUserName" name="nom" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editUserRole" class="form-label">Rôle</label>
                        <select name="role" id="editUserRole" class="form-select">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                            <option value="manager">Manager</option>
                            <option value="moderator">Modérateur</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="updateUser()">
                    Mettre à jour
                </button>
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

.btn-permissions {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.btn-permissions:hover {
    background: linear-gradient(135deg, #138496, #117a8b);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
}

.btn-edit {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: white;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #e0a800, #d39e00);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
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

/* Style pour les boutons outline (gardé pour compatibilité) */
.btn-outline-primary:hover {
    background-color: #003366;
    border-color: #003366;
    color: white;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}
</style>

<script>
function openPermissionsModal(userId, userName) {
    document.getElementById('userId').value = userId;
    document.getElementById('userName').textContent = userName;
    
    // Charger les permissions de l'utilisateur
    loadUserPermissions(userId);
    
    // Charger les informations actuelles de l'utilisateur
    loadUserInfo(userId);
    
    const modal = new bootstrap.Modal(document.getElementById('permissionsModal'));
    modal.show();
}

function openEditModal(userId) {
    // Charger les informations de l'utilisateur
    loadUserInfo(userId);
    
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

function loadUserPermissions(userId) {
    // Ici tu peux faire un appel AJAX pour charger les permissions actuelles
    // Pour l'instant, on affiche toutes les permissions disponibles
    fetch(`/admin/users/${userId}/permissions`)
        .then(response => response.json())
        .then(data => {
            displayPermissions(data.permissions, data.userPermissions);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function loadUserInfo(userId) {
    // Charger les informations de l'utilisateur pour les modals
    fetch(`/admin/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editUserName').value = data.nom;
            document.getElementById('editUserEmail').value = data.email;
            document.getElementById('editUserRole').value = data.role;
            document.getElementById('userRole').value = data.role;
            document.getElementById('userType').value = data.type_utilisateur;
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function displayPermissions(allPermissions, userPermissions) {
    const container = document.getElementById('permissionsList');
    container.innerHTML = '';
    
    allPermissions.forEach(permission => {
        const isChecked = userPermissions.includes(permission.id);
        const div = document.createElement('div');
        div.className = 'form-check mb-2';
        div.innerHTML = `
            <input class="form-check-input" type="checkbox" name="permissions[]" 
                   value="${permission.id}" id="perm_${permission.id}" ${isChecked ? 'checked' : ''}>
            <label class="form-check-label" for="perm_${permission.id}">
                ${permission.name}
            </label>
        `;
        container.appendChild(div);
    });
}

function saveUserPermissions() {
    const form = document.getElementById('permissionsForm');
    const formData = new FormData(form);
    
    fetch('/admin/users/permissions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Permissions mises à jour avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('permissionsModal')).hide();
            location.reload(); // Recharger la page pour voir les changements
        } else {
            alert('Erreur lors de la mise à jour des permissions');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour des permissions');
    });
}

function updateUser() {
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);
    
    fetch(`/admin/users/${document.getElementById('editUserId').value}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Utilisateur mis à jour avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour de l\'utilisateur');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour de l\'utilisateur');
    });
}

function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Utilisateur supprimé avec succès !');
                location.reload();
            } else {
                alert('Erreur lors de la suppression de l\'utilisateur');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'utilisateur');
        });
    }
}

// Charger le nombre d'utilisateurs en attente
document.addEventListener('DOMContentLoaded', function() {
    loadPendingCount();
});

function loadPendingCount() {
    fetch('{{ route("admin.users.pending") }}')
        .then(response => response.json())
        .then(data => {
            const pendingBadge = document.getElementById('pendingBadge');
            if (pendingBadge) {
                pendingBadge.textContent = data.length;
                // Masquer le badge s'il n'y a pas d'utilisateurs en attente
                if (data.length === 0) {
                    pendingBadge.style.display = 'none';
                } else {
                    pendingBadge.style.display = 'inline';
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}
</script>
@endsection