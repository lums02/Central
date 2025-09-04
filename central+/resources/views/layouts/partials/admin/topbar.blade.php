<header class="topbar">
    <div class="topbar-content">
        <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
        <div class="user-section">
            {{-- Affiche le nom si connecté, sinon 'Invité' --}}
            <span class="welcome-text">
                Bienvenue, {{ optional(Auth::user())->name ?? 'Invité' }}
            </span>

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
