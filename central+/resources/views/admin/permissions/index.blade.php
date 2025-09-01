@extends('layouts.admin')

@section('title', 'Gestion des Rôles - CENTRAL+')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Gestion des Rôles</h1>
                    <button type="button" class="btn" style="background: white; color: #003366; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        + Nouveau Rôle
                    </button>
                </div>
            </div>

            <!-- Tableau des rôles -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">#</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Nom du Rôle</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Créé le</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 1rem; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">{{ $role->name }}</td>
                                    <td style="padding: 1rem; vertical-align: middle; color: #6c757d;">{{ $role->created_at->format('d/m/Y') }}</td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <div class="action-buttons">
                                            <button class="btn btn-icon btn-edit"
                                                onclick="editRole('{{ $role->id }}', '{{ $role->name }}')"
                                                title="Modifier le rôle">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color: #6c757d;">
                                        Aucun rôle créé pour le moment.
                                        <span style="color: #003366;">Utilisez le bouton "Nouveau Rôle" ci-dessus pour créer votre premier rôle</span>
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

<!-- Modal pour créer un nouveau rôle -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="createRoleModalLabel">Créer un Nouveau Rôle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createRoleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Nom du rôle</label>
                        <input type="text" class="form-control" id="roleName" name="name" placeholder="Ex: manager_hopital" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="createRole()">Créer le Rôle</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer un rôle et attribuer des permissions -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="editRoleModalLabel">Modifier le Rôle: <span id="editRoleName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" id="editRoleId" name="role_id">

                    <!-- Nom du rôle -->
                    <div class="mb-3">
                        <label for="editRoleNameInput" class="form-label">Nom du rôle</label>
                        <input type="text" class="form-control" id="editRoleNameInput" name="name" required>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Attribuer des Permissions</label>

                        <!-- En-tête des actions CRUD -->
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <strong>Module</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Voir</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Créer</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Modifier</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Supprimer</strong>
                            </div>
                        </div>

                        <!-- Gérer les Rôles et Permissions -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Rôles et Permissions</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_roles" id="perm_view_roles">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_roles" id="perm_create_roles">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_roles" id="perm_edit_roles">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_roles" id="perm_delete_roles">
                            </div>
                        </div>

                        <!-- Gérer les Utilisateurs -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Utilisateurs</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_users" id="perm_view_users">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_users" id="perm_create_users">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_users" id="perm_edit_users">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_users" id="perm_delete_users">
                            </div>
                        </div>

                        <!-- Gérer les Patients -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Patients</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_patients" id="perm_view_patients">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_patients" id="perm_create_patients">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_patients" id="perm_edit_patients">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_patients" id="perm_delete_patients">
                            </div>
                        </div>

                        <!-- Gérer les Rendez-vous -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Rendez-vous</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_appointments" id="perm_view_appointments">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_appointments" id="perm_create_appointments">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_appointments" id="perm_edit_appointments">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_appointments" id="perm_delete_appointments">
                            </div>
                        </div>

                        <!-- Gérer les Dossiers Médicaux -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Dossiers Médicaux</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_medical_records" id="perm_view_medical_records">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_medical_records" id="perm_create_medical_records">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_medical_records" id="perm_edit_medical_records">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_medical_records" id="perm_delete_medical_records">
                            </div>
                        </div>

                        <!-- Gérer les Prescriptions -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Prescriptions</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_prescriptions" id="perm_view_prescriptions">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_prescriptions" id="perm_create_prescriptions">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_prescriptions" id="perm_edit_prescriptions">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_prescriptions" id="perm_delete_prescriptions">
                            </div>
                        </div>

                        <!-- Gérer les Factures -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Factures</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_invoices" id="perm_view_invoices">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_invoices" id="perm_create_invoices">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_invoices" id="perm_edit_invoices">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_invoices" id="perm_delete_invoices">
                            </div>
                        </div>

                        <!-- Gérer les Rapports -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Rapports</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_reports" id="perm_view_reports">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_reports" id="perm_create_reports">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_reports" id="perm_edit_reports">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_reports" id="perm_delete_reports">
                            </div>
                        </div>

                        <!-- Gérer les Médicaments -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Médicaments</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_medicines" id="perm_view_medicines">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_medicines" id="perm_create_medicines">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_medicines" id="perm_edit_medicines">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_medicines" id="perm_delete_medicines">
                            </div>
                        </div>

                        <!-- Gérer les Stocks -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Stocks</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_stocks" id="perm_view_stocks">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_stocks" id="perm_create_stocks">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_stocks" id="perm_edit_stocks">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_stocks" id="perm_delete_stocks">
                            </div>
                        </div>

                        <!-- Gérer les Donneurs de Sang -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Donneurs de Sang</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_donors" id="perm_view_donors">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_donors" id="perm_create_donors">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_donors" id="perm_edit_donors">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_donors" id="perm_delete_donors">
                            </div>
                        </div>

                        <!-- Gérer les Réserves de Sang -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Réserves de Sang</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_blood_reserves" id="perm_view_blood_reserves">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_blood_reserves" id="perm_create_blood_reserves">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_blood_reserves" id="perm_edit_blood_reserves">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_blood_reserves" id="perm_delete_blood_reserves">
                            </div>
                        </div>

                        <!-- Gérer les Services -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Services</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_services" id="perm_view_services">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_services" id="perm_create_services">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_services" id="perm_edit_services">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_services" id="perm_delete_services">
                            </div>
                        </div>

                        <!-- Gérer les Consultations -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <strong>Gérer les Consultations</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_consultations" id="perm_view_consultations">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_consultations" id="perm_create_consultations">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_consultations" id="perm_edit_consultations">
                            </div>
                            <div class="col-md-2 text-center">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_consultations" id="perm_delete_consultations">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="updateRole()">Mettre à jour</button>
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
    function createRole() {
        const form = document.getElementById('createRoleForm');
        const formData = new FormData(form);

        fetch('{{ route("admin.permissions.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Rôle créé avec succès !');
                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createRoleModal'));
                    modal.hide();
                    // Recharger la page pour voir le nouveau rôle
                    location.reload();
                } else {
                    alert('Erreur lors de la création du rôle : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la création du rôle');
            });
    }

    // Réinitialiser le formulaire quand le modal se ferme
    document.getElementById('createRoleModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('createRoleForm').reset();
    });

    // Fonction pour éditer un rôle
    function editRole(roleId, roleName) {
        // Remplir le modal avec les données du rôle
        document.getElementById('editRoleId').value = roleId;
        document.getElementById('editRoleName').textContent = roleName;
        document.getElementById('editRoleNameInput').value = roleName;

        // Réinitialiser toutes les checkboxes
        const checkboxes = document.querySelectorAll('#editRoleForm input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Charger les permissions actuelles du rôle (à implémenter plus tard)
        // loadRolePermissions(roleId);

        // Ouvrir le modal
        const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        editModal.show();
    }

    // Fonction pour mettre à jour un rôle
    function updateRole() {
        const form = document.getElementById('editRoleForm');
        const formData = new FormData(form);

        // Récupérer les permissions sélectionnées
        const selectedPermissions = [];
        const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
        checkboxes.forEach(checkbox => {
            selectedPermissions.push(checkbox.value);
        });

        // Ajouter les permissions au formData
        formData.append('permissions', JSON.stringify(selectedPermissions));

        fetch('{{ route("admin.permissions.update", ":id") }}'.replace(':id', document.getElementById('editRoleId').value), {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Rôle mis à jour avec succès !');
                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editRoleModal'));
                    modal.hide();
                    // Recharger la page pour voir les changements
                    location.reload();
                } else {
                    alert('Erreur lors de la mise à jour du rôle : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour du rôle');
            });
    }

    // Fonction pour supprimer un rôle
    function deleteRole(roleId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')) {
            fetch('{{ route("admin.permissions.destroy", ":id") }}'.replace(':id', roleId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Rôle supprimé avec succès !');
                        // Recharger la page pour voir les changements
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression : ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression du rôle');
                });
        }
    }

    // Ajouter le CSS pour les boutons d'action
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.textContent = `
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
    `;
        document.head.appendChild(style);
    });
</script>
@endsection