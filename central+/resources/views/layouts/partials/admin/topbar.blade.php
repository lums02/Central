<header class="topbar">
    <div class="topbar-content">
        <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
        <div class="user-section">
            {{-- Affiche le nom de l'entité et l'utilisateur si connecté --}}
            @auth
                <span class="entity-name" style="font-weight: bold; color: #003366; margin-right: 15px;">
                    {{ Auth::user()->getEntiteName() }}
                </span>
                <span class="welcome-text">
                    Bienvenue, {{ Auth::user()->nom ?? 'Utilisateur' }}
                </span>
            @else
                <span class="welcome-text">
                    Bienvenue, Invité
                </span>
            @endauth

            {{-- Bouton de déconnexion, uniquement si connecté --}}
            @auth
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Déconnexion</button>
                </form>
            @endauth
        </div>
    </div>
</header>
