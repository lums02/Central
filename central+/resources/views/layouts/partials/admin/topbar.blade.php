<header class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
    <h5>@yield('page-title', 'Dashboard')</h5>
    <div class="d-flex align-items-center">
        {{-- Affiche le nom si connecté, sinon 'Invité' --}}
        <span>
            Bienvenue, {{ optional(Auth::user())->name ?? 'Invité' }}
        </span>

        {{-- Bouton de déconnexion, uniquement si connecté --}}
        @auth
            <form action="{{ route('logout') }}" method="POST" class="ms-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Déconnexion</button>
            </form>
        @endauth
    </div>
</header>
