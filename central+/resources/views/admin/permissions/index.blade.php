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
    <div class="modal-dialog modal-xl">
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

                        <!-- Permissions dynamiques -->
                        <div id="permissionsContainer">
                            <!-- Les permissions seront générées dynamiquement ici -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="updateRole()">
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

        // Charger les permissions dynamiquement
        loadRolePermissions(roleId);

        // Ouvrir le modal
        const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        editModal.show();
    }

    // Fonction pour charger les permissions d'un rôle
    function loadRolePermissions(roleId) {
        fetch(`/admin/permissions/${roleId}/permissions`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                generatePermissionsUI(data.permissions, data.role_permissions);
            } else {
                console.error('Erreur lors du chargement des permissions:', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }

    // Fonction pour générer l'interface des permissions
    function generatePermissionsUI(allPermissions, rolePermissions = []) {
        const container = document.getElementById('permissionsContainer');
        container.innerHTML = '';

        // Grouper les permissions par module
        const groupedPermissions = groupPermissionsByModule(allPermissions);

        Object.keys(groupedPermissions).forEach(moduleName => {
            const modulePermissions = groupedPermissions[moduleName];
            const displayName = getModuleDisplayName(moduleName);

            // Créer la ligne pour ce module
            const moduleRow = document.createElement('div');
            moduleRow.className = 'row mb-3 align-items-center';
            moduleRow.innerHTML = `
                <div class="col-md-3">
                    <strong>${displayName}</strong>
                </div>
                <div class="col-md-2 text-center">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="${moduleName}_view" id="perm_${moduleName}_view" ${rolePermissions.includes(moduleName + '_view') ? 'checked' : ''}>
                </div>
                <div class="col-md-2 text-center">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="${moduleName}_create" id="perm_${moduleName}_create" ${rolePermissions.includes(moduleName + '_create') ? 'checked' : ''}>
                </div>
                <div class="col-md-2 text-center">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="${moduleName}_edit" id="perm_${moduleName}_edit" ${rolePermissions.includes(moduleName + '_edit') ? 'checked' : ''}>
                </div>
                <div class="col-md-2 text-center">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="${moduleName}_delete" id="perm_${moduleName}_delete" ${rolePermissions.includes(moduleName + '_delete') ? 'checked' : ''}>
                </div>
            `;

            container.appendChild(moduleRow);
        });
    }

    // Fonction pour grouper les permissions par module
    function groupPermissionsByModule(permissions) {
        const grouped = {};
        
        permissions.forEach(permission => {
            const parts = permission.name.split('_');
            if (parts.length >= 2) {
                const action = parts[0]; // view, create, edit, delete
                const module = parts.slice(1).join('_'); // le reste forme le nom du module
                
                if (!grouped[module]) {
                    grouped[module] = [];
                }
                grouped[module].push(permission);
            }
        });

        return grouped;
    }

    // Fonction pour obtenir le nom d'affichage d'un module
    function getModuleDisplayName(moduleName) {
        const displayNames = {
            'roles': 'Gérer les Rôles et Permissions',
            'users': 'Gérer les Utilisateurs',
            'patients': 'Gérer les Patients',
            'appointments': 'Gérer les Rendez-vous',
            'medical_records': 'Gérer les Dossiers Médicaux',
            'prescriptions': 'Gérer les Prescriptions',
            'invoices': 'Gérer les Factures',
            'reports': 'Gérer les Rapports',
            'medicines': 'Gérer les Médicaments',
            'stocks': 'Gérer les Stocks',
            'donors': 'Gérer les Donneurs',
            'consultations': 'Gérer les Consultations',
            'hopital': 'Gérer les Hôpitaux',
            'pharmacie': 'Gérer les Pharmacies',
            'banque_sang': 'Gérer les Banques de Sang',
            'centre': 'Gérer les Centres',
            'patient': 'Gérer les Patients'
        };

        return displayNames[moduleName] || `Gérer les ${moduleName.charAt(0).toUpperCase() + moduleName.slice(1)}`;
    }

    // Fonction pour mettre à jour un rôle
    function updateRole() {
        const form = document.getElementById('editRoleForm');
        const formData = new FormData(form);
        const roleId = document.getElementById('editRoleId').value;

        // Récupérer les permissions sélectionnées
        const selectedPermissions = [];
        const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
        checkboxes.forEach(checkbox => {
            selectedPermissions.push(checkbox.value);
        });

        // Ajouter les permissions au formData
        formData.append('permissions', JSON.stringify(selectedPermissions));

        console.log('Mise à jour du rôle:', roleId);
        console.log('Permissions sélectionnées:', selectedPermissions);

        const url = '{{ route("admin.permissions.update", ":id") }}'.replace(':id', roleId);
        console.log('URL:', url);

        fetch(url, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
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