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
                                            @if($utilisateur->role !== 'superadmin' && $utilisateur->email !== 'admin@central.com')
                                                <button class="btn btn-icon btn-delete" onclick="deleteUser({{ $utilisateur->id }})" title="Supprimer l'utilisateur">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-icon btn-delete" disabled title="Le superadmin ne peut pas être supprimé" style="opacity: 0.5; cursor: not-allowed;">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                            @endif
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

<!-- Modal pour les permissions (format tableau CRUD) -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="permissionsModalLabel">Gérer les Permissions - <span id="userName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="permissionsForm">
                    @csrf
                    <input type="hidden" id="userId" name="user_id">
                    
                    <!-- Informations de l'utilisateur -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rôle de l'utilisateur</label>
                                <select name="role" id="userRole" class="form-select">
                                    <option value="user">Utilisateur</option>
                                    <option value="admin">Administrateur</option>
                                    <option value="manager">Manager</option>
                                    <option value="moderator">Modérateur</option>
                                    <option value="superadmin">Super Administrateur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Type d'utilisateur</label>
                                <select name="type_utilisateur" id="userType" class="form-select">
                                    <option value="hopital">Hôpital</option>
                                    <option value="pharmacie">Pharmacie</option>
                                    <option value="banque_sang">Banque de Sang</option>
                                    <option value="centre">Centre</option>
                                    <option value="patient">Patient</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Attribuer des Permissions</label>

                        <!-- En-tête des actions CRUD -->
                        <div class="row mb-2">
                            <div class="col-md-3"><strong>Module</strong></div>
                            <div class="col-md-2 text-center"><strong>Voir</strong></div>
                            <div class="col-md-2 text-center"><strong>Créer</strong></div>
                            <div class="col-md-2 text-center"><strong>Modifier</strong></div>
                            <div class="col-md-2 text-center"><strong>Supprimer</strong></div>
                        </div>

                        <!-- Gérer les Rôles et Permissions -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Rôles et Permissions</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_roles" id="perm_view_roles"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_roles" id="perm_create_roles"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_roles" id="perm_edit_roles"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_roles" id="perm_delete_roles"></div>
                        </div>

                        <!-- Gérer les Utilisateurs -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Utilisateurs</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_users" id="perm_view_users"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_users" id="perm_create_users"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_users" id="perm_edit_users"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_users" id="perm_delete_users"></div>
                        </div>

                        <!-- Gérer les Patients -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Patients</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_patients" id="perm_view_patients"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_patients" id="perm_create_patients"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_patients" id="perm_edit_patients"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_patients" id="perm_delete_patients"></div>
                        </div>

                        <!-- Gérer les Rendez-vous -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Rendez-vous</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_appointments" id="perm_view_appointments"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_appointments" id="perm_create_appointments"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_appointments" id="perm_edit_appointments"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_appointments" id="perm_delete_appointments"></div>
                        </div>

                        <!-- Gérer les Dossiers Médicaux -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Dossiers Médicaux</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_medical_records" id="perm_view_medical_records"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_medical_records" id="perm_create_medical_records"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_medical_records" id="perm_edit_medical_records"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_medical_records" id="perm_delete_medical_records"></div>
                        </div>

                        <!-- Gérer les Prescriptions -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Prescriptions</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_prescriptions" id="perm_view_prescriptions"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_prescriptions" id="perm_create_prescriptions"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_prescriptions" id="perm_edit_prescriptions"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_prescriptions" id="perm_delete_prescriptions"></div>
                        </div>

                        <!-- Gérer les Factures -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Factures</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_invoices" id="perm_view_invoices"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_invoices" id="perm_create_invoices"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_invoices" id="perm_edit_invoices"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_invoices" id="perm_delete_invoices"></div>
                        </div>

                        <!-- Gérer les Rapports -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Rapports</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_reports" id="perm_view_reports"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_reports" id="perm_create_reports"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_reports" id="perm_edit_reports"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_reports" id="perm_delete_reports"></div>
                        </div>

                        <!-- Gérer les Médicaments -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Médicaments</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_medicines" id="perm_view_medicines"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_medicines" id="perm_create_medicines"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_medicines" id="perm_edit_medicines"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_medicines" id="perm_delete_medicines"></div>
                        </div>

                        <!-- Gérer les Stocks -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Stocks</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_stocks" id="perm_view_stocks"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_stocks" id="perm_create_stocks"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_stocks" id="perm_edit_stocks"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_stocks" id="perm_delete_stocks"></div>
                        </div>

                        <!-- Gérer les Donneurs de Sang -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Donneurs de Sang</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_donors" id="perm_view_donors"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_donors" id="perm_create_donors"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_donors" id="perm_edit_donors"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_donors" id="perm_delete_donors"></div>
                        </div>

                        <!-- Gérer les Réserves de Sang -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Réserves de Sang</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_blood_reserves" id="perm_view_blood_reserves"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_blood_reserves" id="perm_create_blood_reserves"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_blood_reserves" id="perm_edit_blood_reserves"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_blood_reserves" id="perm_delete_blood_reserves"></div>
                        </div>

                        <!-- Gérer les Services -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Services</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_services" id="perm_view_services"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_services" id="perm_create_services"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_services" id="perm_edit_services"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_services" id="perm_delete_services"></div>
                        </div>

                        <!-- Gérer les Consultations -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3"><strong>Gérer les Consultations</strong></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_consultations" id="perm_view_consultations"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_consultations" id="perm_create_consultations"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_consultations" id="perm_edit_consultations"></div>
                            <div class="col-md-2 text-center"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_consultations" id="perm_delete_consultations"></div>
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
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="editUserModalLabel">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
/* Styles pour les modales Bootstrap 5 */
.modal {
    display: none;
}

.modal.show {
    display: block;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}

/* Styles pour le tableau */
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

/* Styles pour le modal des permissions */
.modal-xl {
    max-width: 1200px;
}

.form-check-input {
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #003366;
    border-color: #003366;
}

.row.align-items-center {
    border-bottom: 1px solid #f0f0f0;
    padding: 0.5rem 0;
}

.row.align-items-center:hover {
    background-color: #f8f9fa;
}

.row.align-items-center:last-child {
    border-bottom: none;
}
</style>

<script>
// Récupérer le token CSRF depuis la meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
    // Pour le nouveau format, on utilise setDefaultPermissions
    setDefaultPermissions();
}

function loadUserInfo(userId) {
    // Charger les informations de l'utilisateur pour les modals
    fetch(`/admin/users/${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
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
            alert('Erreur lors du chargement des informations utilisateur');
        });
}

// Fonction pour définir les permissions par défaut selon le type d'entité
function setDefaultPermissions() {
    const userType = document.getElementById('userType').value;
    const userRole = document.getElementById('userRole').value;
    
    // Réinitialiser toutes les checkboxes
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Si c'est un superadmin, cocher toutes les permissions
    if (userRole === 'superadmin') {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            checkbox.disabled = true; // Désactiver les checkboxes pour le superadmin
        });
        
        // Afficher un message informatif
        const permissionsSection = document.querySelector('.mb-3');
        let infoDiv = document.getElementById('superadmin-info');
        if (!infoDiv) {
            infoDiv = document.createElement('div');
            infoDiv.id = 'superadmin-info';
            infoDiv.className = 'alert alert-info mt-3';
            infoDiv.innerHTML = '<i class="fas fa-shield-alt me-2"></i>Le Super Administrateur a automatiquement toutes les permissions.';
            permissionsSection.appendChild(infoDiv);
        }
        infoDiv.style.display = 'block';
        
        return;
    } else {
        // Masquer le message informatif pour les autres rôles
        const infoDiv = document.getElementById('superadmin-info');
        if (infoDiv) {
            infoDiv.style.display = 'none';
        }
        
        // Réactiver toutes les checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.disabled = false;
        });
    }
    
    // Permissions par défaut pour un administrateur
    if (userRole === 'admin') {
        // Permissions de base pour tous les administrateurs
        const defaultPermissions = [
            'view_users', 'create_users', 'edit_users',
            'view_patients', 'create_patients', 'edit_patients',
            'view_appointments', 'create_appointments', 'edit_appointments',
            'view_medical_records', 'create_medical_records', 'edit_medical_records',
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
            'view_reports', 'create_reports'
        ];
        
        // Permissions spécifiques selon le type d'entité
        switch(userType) {
            case 'hopital':
                defaultPermissions.push(
                    'view_consultations', 'create_consultations', 'edit_consultations',
                    'view_services', 'create_services', 'edit_services'
                );
                break;
            case 'pharmacie':
                defaultPermissions.push(
                    'view_medicines', 'create_medicines', 'edit_medicines',
                    'view_stocks', 'create_stocks', 'edit_stocks',
                    'view_invoices', 'create_invoices', 'edit_invoices'
                );
                break;
            case 'banque_sang':
                defaultPermissions.push(
                    'view_donors', 'create_donors', 'edit_donors',
                    'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves'
                );
                break;
        }
        
        // Cocher les permissions par défaut
        defaultPermissions.forEach(permission => {
            const checkbox = document.getElementById(`perm_${permission}`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
}

function saveUserPermissions() {
    const form = document.getElementById('permissionsForm');
    const formData = new FormData(form);
    
    // Récupérer les permissions sélectionnées
    const selectedPermissions = [];
    const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
    checkboxes.forEach(checkbox => {
        selectedPermissions.push(checkbox.value);
    });
    
    // Ajouter les permissions au formData
    formData.append('permissions', JSON.stringify(selectedPermissions));
    
    fetch('/admin/users/permissions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Permissions mises à jour avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('permissionsModal')).hide();
            location.reload(); // Recharger la page pour voir les changements
        } else {
            alert('Erreur lors de la mise à jour des permissions: ' + (data.message || 'Erreur inconnue'));
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
    const userId = document.getElementById('editUserId').value;
    
    fetch(`/admin/users/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Utilisateur mis à jour avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour de l\'utilisateur: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour de l\'utilisateur');
    });
}

function deleteUser(userId) {
    // Vérifier si c'est le superadmin avant de supprimer
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (userRow) {
        const userEmail = userRow.querySelector('td:nth-child(3)').textContent.trim();
        const userRole = userRow.querySelector('td:nth-child(5)').textContent.trim();
        
        if (userEmail === 'admin@central.com' || userRole === 'Super Administrateur') {
            alert('Impossible de supprimer le Super Administrateur !');
            return;
        }
    }
    
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Utilisateur supprimé avec succès !');
                location.reload();
            } else {
                alert('Erreur lors de la suppression de l\'utilisateur: ' + (data.message || 'Erreur inconnue'));
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
    console.log('DOM chargé - Test Bootstrap 5');
    
    // Vérifier si Bootstrap est chargé
    if (typeof bootstrap !== 'undefined') {
        console.log('✅ Bootstrap 5 est chargé');
    } else {
        console.log('❌ Bootstrap 5 n\'est pas chargé');
    }
    
    // Vérifier les modales
    const permissionsModal = document.getElementById('permissionsModal');
    const editUserModal = document.getElementById('editUserModal');
    
    if (permissionsModal) {
        console.log('✅ Modal permissions trouvée');
    } else {
        console.log('❌ Modal permissions manquante');
    }
    
    if (editUserModal) {
        console.log('✅ Modal edit trouvée');
    } else {
        console.log('❌ Modal edit manquante');
    }
    
    // Événements pour mettre à jour les permissions par défaut
    document.getElementById('userRole').addEventListener('change', setDefaultPermissions);
    document.getElementById('userType').addEventListener('change', setDefaultPermissions);
    
    loadPendingCount();
});

function loadPendingCount() {
    fetch('{{ route("admin.users.pending") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const pendingBadge = document.getElementById('pendingBadge');
            if (pendingBadge) {
                pendingBadge.textContent = data.length || 0;
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
            // En cas d'erreur, masquer le badge
            const pendingBadge = document.getElementById('pendingBadge');
            if (pendingBadge) {
                pendingBadge.style.display = 'none';
            }
        });
}
</script>
@endsection