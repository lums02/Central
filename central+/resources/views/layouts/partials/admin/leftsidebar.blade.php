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
               class="nav-link text-white mb-2 {{ request()->is('index') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-home me-2"></i> Tableau de bord
            </a>
            <a href="{{ route('admin.permissions.index') }}"
               class="nav-link text-white mb-2 {{ request()->is('admin/permissions*') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-shield-alt me-2"></i> Rôles et Permissions
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link text-white mb-2 {{ request()->is('admin/users*') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-users me-2"></i> Utilisateur
            </a>
            <a href="{{ route('admin.users.pending') }}"
               class="nav-link text-white mb-2 {{ request()->is('admin/users/pending*') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-clock me-2"></i> En Attente
                <span class="badge bg-warning text-dark ms-auto" id="pendingBadge">0</span>
            </a>
            <a href="{{ route('admin.entities') }}"
               class="nav-link text-white mb-2 {{ request()->is('notif') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-building me-2"></i> Entités
            </a>
            <a href="{{ route('admin.settings') }}"
               class="nav-link text-white mb-2 {{ request()->is('rdv') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-cog me-2"></i> Paramètres
            </a>
        </nav>
    </div>
</div>


<!-- Overlay to close sidebar on mobile -->
<div id="sidebarOverlay"></div>

<script>
    
</script>
