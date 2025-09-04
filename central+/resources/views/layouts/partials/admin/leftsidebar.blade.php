<!-- resources/views/layouts/partials/admin/leftsidebar.blade.php -->
<div>
    <!-- Bouton hamburger (visible sur petits écrans) -->
    <button id="sidebarToggle" class="btn btn-primary d-md-none m-2">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="hospital-logo">
                <span>C+</span>
            </div>
            <h3>CENTRAL+</h3>
        </div>

        <nav class="nav flex-column mt-3 px-2 flex-grow-1">
            {{-- Tableau de bord - Toujours visible --}}
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link text-white mb-2 {{ request()->routeIs('admin.dashboard') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-home me-2"></i> Tableau de bord
            </a>

            {{-- Rôles et Permissions - Toujours visible pour le superadmin, sinon selon les permissions --}}
            @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->can('view_roles')))
            <a href="{{ route('admin.permissions.index') }}"
               class="nav-link text-white mb-2 {{ request()->routeIs('admin.permissions*') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-shield-alt me-2"></i> Rôles et Permissions
            </a>
            @endif

            {{-- Gestion des Utilisateurs - Seulement si l'utilisateur a les permissions --}}
            @if(auth()->check() && auth()->user()->can('view_users'))
            <a href="{{ route('admin.users.index') }}"
               class="nav-link text-white mb-2 {{ request()->routeIs('admin.users.index') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-users me-2"></i> Utilisateur
            </a>
            @endif

            {{-- Utilisateurs en Attente - Seulement si l'utilisateur peut voir les utilisateurs --}}
            @if(auth()->check() && auth()->user()->can('view_users'))
            <a href="{{ route('admin.users.pending') }}"
               class="nav-link text-white mb-2 {{ request()->routeIs('admin.users.pending') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-clock me-2"></i> En Attente
                <span class="badge bg-warning text-dark ms-auto" id="pendingBadge">0</span>
            </a>
            @endif

            {{-- Modules spécifiques aux entités - Seulement pour les non-superadmins --}}
            @if(auth()->check() && !auth()->user()->isSuperAdmin())
                {{-- Gestion des Patients - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_patients') && Route::has('admin.patients.index'))
                <a href="{{ route('admin.patients.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.patients*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-user-injured me-2"></i> Patients
                </a>
                @endif

                {{-- Gestion des Rendez-vous - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_appointments') && Route::has('admin.appointments.index'))
                <a href="{{ route('admin.appointments.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.appointments*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-calendar-alt me-2"></i> Rendez-vous
                </a>
                @endif

                {{-- Gestion des Dossiers Médicaux - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_medical_records') && Route::has('admin.medical-records.index'))
                <a href="{{ route('admin.medical-records.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.medical_records*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-file-medical me-2"></i> Dossiers Médicaux
                </a>
                @endif

                {{-- Gestion des Prescriptions - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_prescriptions') && Route::has('admin.prescriptions.index'))
                <a href="{{ route('admin.prescriptions.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.prescriptions*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-prescription me-2"></i> Prescriptions
                </a>
                @endif

                {{-- Gestion des Factures - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_invoices') && Route::has('admin.invoices.index'))
                <a href="{{ route('admin.invoices.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.invoices*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-file-invoice me-2"></i> Factures
                </a>
                @endif

                {{-- Gestion des Rapports - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_reports') && Route::has('admin.reports.index'))
                <a href="{{ route('admin.reports.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.reports*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i> Rapports
                </a>
                @endif

                {{-- Gestion des Médicaments - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_medicines') && Route::has('admin.medicines.index'))
                <a href="{{ route('admin.medicines.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.medicines*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-pills me-2"></i> Médicaments
                </a>
                @endif

                {{-- Gestion des Stocks - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_stocks') && Route::has('admin.stocks.index'))
                <a href="{{ route('admin.stocks.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.stocks*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-boxes me-2"></i> Stocks
                </a>
                @endif

                {{-- Gestion des Donneurs - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_donors') && Route::has('admin.donors.index'))
                <a href="{{ route('admin.donors.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.donors*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-heart me-2"></i> Donneurs
                </a>
                @endif

                {{-- Gestion des Réserves de Sang - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_blood_reserves') && Route::has('admin.blood-reserves.index'))
                <a href="{{ route('admin.blood-reserves.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.blood_reserves*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-tint me-2"></i> Réserves de Sang
                </a>
                @endif

                {{-- Gestion des Services - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_services') && Route::has('admin.services.index'))
                <a href="{{ route('admin.services.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.services*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-concierge-bell me-2"></i> Services
                </a>
                @endif

                {{-- Gestion des Consultations - Seulement si l'utilisateur a les permissions ET si la route existe --}}
                @if(auth()->user()->can('view_consultations') && Route::has('admin.consultations.index'))
                <a href="{{ route('admin.consultations.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.consultations*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-stethoscope me-2"></i> Consultations
                </a>
                @endif
            @endif

            {{-- Entités - Seulement pour les superadmins ou admins --}}
            @if(auth()->check() && (auth()->user()->can('view_roles') || auth()->user()->isSuperAdmin()))
            <a href="{{ route('admin.entities') }}"
               class="nav-link text-white mb-2 {{ request()->routeIs('admin.entities') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-building me-2"></i> Entités
            </a>
            @endif

            {{-- Paramètres - Seulement pour les superadmins ou admins --}}
            @if(auth()->check() && (auth()->user()->can('view_roles') || auth()->user()->isSuperAdmin()))
            <a href="{{ route('admin.settings') }}"
               class="nav-link text-white mb-2 {{ request()->routeIs('admin.settings') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-cog me-2"></i> Paramètres
            </a>
            @endif
        </nav>
    </div>
</div>

<!-- Overlay to close sidebar on mobile -->
<div id="sidebarOverlay"></div>

<script>
// Charger le nombre d'utilisateurs en attente au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadPendingCount();
    
    // Actualiser le badge toutes les 30 secondes
    setInterval(loadPendingCount, 30000);
});

// Fonction pour charger le nombre d'utilisateurs en attente
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
                const count = data.length || 0;
                pendingBadge.textContent = count;
                
                // Afficher ou masquer le badge selon le nombre
                if (count > 0) {
                    pendingBadge.style.display = 'inline';
                    pendingBadge.textContent = count;
                    
                    // Animation pour attirer l'attention
                    if (count > 5) {
                        pendingBadge.classList.add('pulse');
                    }
                } else {
                    pendingBadge.style.display = 'none';
                    pendingBadge.classList.remove('pulse');
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement du badge:', error);
            // En cas d'erreur, masquer le badge
            const pendingBadge = document.getElementById('pendingBadge');
            if (pendingBadge) {
                pendingBadge.style.display = 'none';
            }
        });
}

// Toggle sidebar sur mobile
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
        overlay.style.display = 'none';
    } else {
        sidebar.classList.add('active');
        overlay.style.display = 'block';
    }
});

// Fermer sidebar en cliquant sur l'overlay
document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.remove('active');
    overlay.style.display = 'none';
});
</script>

<style>
/* Animation pour le badge de notification */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.pulse {
    animation: pulse 2s infinite;
}

/* Responsive pour le sidebar */
@media (max-width: 768px) {
    #sidebarOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
}
</style>
