<!-- resources/views/layouts/partials/admin/leftsidebar.blade.php -->
<div>
    <!-- Bouton hamburger (visible sur petits écrans) -->
    <button id="sidebarToggle" class="btn btn-primary d-md-none m-2">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar bg-dark text-white vh-100 position-fixed start-0 top-0 d-flex flex-column"
         style="width: 250px; transform: translateX(0); transition: transform 0.3s ease;">
        <div class="sidebar-header p-3 text-center border-bottom border-secondary">
            <div class="hospital-logo mx-auto mb-2"
                 style="width: 60px; height: 60px; border-radius: 50%; background-color: #0d6efd; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                C+
            </div>
            <h3>CENTRAL+</h3>
        </div>

        <nav class="nav flex-column mt-3 px-2 flex-grow-1">
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
                <i class="fas fa-users me-2"></i> Utilisateur
            </a>
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
    
    if (sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
        overlay.style.display = 'none';
    } else {
        sidebar.classList.add('show');
        overlay.style.display = 'block';
    }
});

// Fermer sidebar en cliquant sur l'overlay
document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.remove('show');
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
    #sidebar {
        transform: translateX(-100%);
        z-index: 1050;
    }
    
    #sidebar.show {
        transform: translateX(0);
    }
    
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
