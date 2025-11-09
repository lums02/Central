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
            @if(auth()->check())
                {{-- ========== COMMUN À TOUS (Données isolées par entité) ========== --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.dashboard') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-home me-2"></i> Tableau de bord
                </a>

                <a href="{{ route('admin.permissions.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.permissions*') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-shield-alt me-2"></i> Rôles et Permissions
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="nav-link text-white mb-2 {{ request()->routeIs('admin.users.index') ? 'active bg-primary rounded' : '' }}">
                    <i class="fas fa-users me-2"></i> Utilisateurs
                </a>

                {{-- ========== SPÉCIFIQUE PAR TYPE D'UTILISATEUR ========== --}}
                @if(auth()->user()->isSuperAdmin())
                    {{-- SUPERADMIN UNIQUEMENT --}}
                    <a href="{{ route('admin.users.pending') }}"
                       class="nav-link text-white mb-2 {{ request()->routeIs('admin.users.pending') ? 'active bg-primary rounded' : '' }}">
                        <i class="fas fa-clock me-2"></i> En Attente
                        <span class="badge bg-warning text-dark ms-auto" id="pendingBadge">0</span>
                    </a>

                    <a href="{{ route('admin.entities') }}"
                       class="nav-link text-white mb-2 {{ request()->routeIs('admin.entities') ? 'active bg-primary rounded' : '' }}">
                        <i class="fas fa-building me-2"></i> Entités
                    </a>

                    <a href="{{ route('admin.settings') }}"
                       class="nav-link text-white mb-2 {{ request()->routeIs('admin.settings') ? 'active bg-primary rounded' : '' }}">
                        <i class="fas fa-cog me-2"></i> Paramètres
                    </a>

                @elseif(auth()->user()->type_utilisateur === 'hopital')
                    {{-- HÔPITAL UNIQUEMENT --}}
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.hopital.patients.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.hopital.patients*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-user-injured me-2"></i> Patients
                        </a>

                        <a href="{{ route('admin.hopital.rendezvous.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.hopital.rendezvous*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-calendar-alt me-2"></i> Rendez-vous
                        </a>

                        <div class="nav-section-title text-white-50 mt-3 mb-2 px-2">
                            <small>TRANSFERTS</small>
                        </div>

                        <a href="{{ route('admin.hopital.transferts.rechercher') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.hopital.transferts.rechercher') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-search me-2"></i> Demander un Dossier
                        </a>

                        <a href="{{ route('admin.hopital.transferts.demandes-envoyees') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.hopital.transferts.demandes-envoyees') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-paper-plane me-2"></i> Demandes Envoyées
                        </a>

                        <a href="{{ route('admin.hopital.transferts.demandes-recues') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.hopital.transferts.demandes-recues') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-inbox me-2"></i> Demandes Reçues
                        </a>
                    @endif

                @elseif(auth()->user()->type_utilisateur === 'pharmacie')
                    {{-- PHARMACIE UNIQUEMENT --}}
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.pharmacie.medicaments.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.pharmacie.medicaments*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-pills me-2"></i> Médicaments
                        </a>

                        <a href="{{ route('admin.pharmacie.stocks.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.pharmacie.stocks*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-boxes me-2"></i> Stocks
                        </a>

                        <a href="{{ route('admin.pharmacie.commandes.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.pharmacie.commandes*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-shopping-cart me-2"></i> Commandes
                        </a>

                        <a href="{{ route('admin.pharmacie.fournisseurs.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.pharmacie.fournisseurs*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-truck me-2"></i> Fournisseurs
                        </a>

                        <a href="{{ route('admin.pharmacie.ventes.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.pharmacie.ventes*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-cash-register me-2"></i> Ventes
                        </a>
                    @endif

                @elseif(auth()->user()->type_utilisateur === 'banque_sang')
                    {{-- BANQUE DE SANG UNIQUEMENT --}}
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.banque-sang.donneurs.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.banque-sang.donneurs*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-user-friends me-2"></i> Donneurs
                        </a>

                        <a href="{{ route('admin.banque-sang.dons.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.banque-sang.dons*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-hand-holding-heart me-2"></i> Dons
                        </a>

                        <a href="{{ route('admin.banque-sang.reserves.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.banque-sang.reserves*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-tint me-2"></i> Réserves
                        </a>

                        <a href="{{ route('admin.banque-sang.demandes.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.banque-sang.demandes*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-file-medical me-2"></i> Demandes
                        </a>

                        <a href="{{ route('admin.banque-sang.analyses.index') }}"
                           class="nav-link text-white mb-2 {{ request()->routeIs('admin.banque-sang.analyses*') ? 'active bg-primary rounded' : '' }}">
                            <i class="fas fa-microscope me-2"></i> Analyses
                        </a>
                    @endif
                @endif
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
