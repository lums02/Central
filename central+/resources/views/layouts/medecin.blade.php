<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Espace Médecin - CENTRAL+')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --central-primary: #003366;
            --central-secondary: #ff6b35;
            --central-light: #f8f9fa;
            --central-dark: #2c3e50;
            --central-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-gradient: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            --card-shadow: 0 8px 32px rgba(0,0,0,0.1);
            --hover-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, var(--central-primary) 0%, #004080 100%);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        .sidebar-header {
            padding: 30px 20px 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 1;
        }
        
        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
        }
        
        .sidebar-header h4 i {
            margin-right: 10px;
            font-size: 1.3rem;
            color: #ffd700;
        }
        
        .sidebar-nav {
            padding: 20px 0;
            position: relative;
            z-index: 1;
        }
        
        .nav-item {
            margin: 8px 0;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0 30px 30px 0;
            margin-right: 20px;
            position: relative;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #0056b3;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.15);
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .nav-link:hover::before {
            transform: scaleY(1);
        }
        
        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #0056b3 0%, #0066cc 100%);
            box-shadow: 0 6px 20px rgba(0,86,179,0.4);
            transform: translateX(5px);
        }
        
        .nav-link.active::before {
            transform: scaleY(1);
            background: white;
        }
        
        .nav-link i {
            margin-right: 15px;
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .topbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            padding: 20px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .topbar h5 {
            margin: 0;
            color: var(--central-primary);
            font-weight: 700;
            font-size: 1.4rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, rgba(0,51,102,0.1) 0%, rgba(0,64,128,0.1) 100%);
            border-radius: 50px;
            border: 1px solid rgba(0,51,102,0.1);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--central-secondary) 0%, #ff8c42 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
        }
        
        .user-details {
            text-align: left;
        }
        
        .user-details .name {
            font-weight: 600;
            color: var(--central-primary);
            font-size: 0.95rem;
        }
        
        .user-details .role {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .content-area {
            padding: 40px;
            background: transparent;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--central-primary) 0%, #004080 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px 25px;
            border: none;
            position: relative;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--central-primary) 0%, #004080 100%);
            box-shadow: 0 4px 15px rgba(0,51,102,0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #004080 0%, #0056b3 100%);
            box-shadow: 0 6px 20px rgba(0,51,102,0.4);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--central-secondary) 0%, #ff8c42 100%);
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #ff8c42 0%, #ffa726 100%);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
            transform: translateY(-2px);
        }
        
        .stats-card {
            background: white;
            color: var(--central-primary);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(0, 51, 102, 0.15);
            border: 1px solid rgba(0, 51, 102, 0.1);
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .stats-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,51,102,0.25);
        }
        
        .stats-card h3 {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0;
            color: var(--central-primary);
            position: relative;
            z-index: 1;
        }
        
        .stats-card p {
            margin: 8px 0 0 0;
            color: var(--central-primary);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }
        
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            color: var(--central-primary);
            padding: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .table tbody td {
            padding: 15px;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: rgba(0,51,102,0.05);
        }
        
        .badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .avatar-sm {
            width: 40px;
            height: 40px;
        }
        
        .avatar-title {
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .content-area {
                padding: 20px;
            }
            
            .topbar {
                padding: 15px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-user-md me-2"></i>{{ auth()->user()->getEntiteName() }}</h4>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.dashboard') ? 'active' : '' }}" href="{{ route('admin.medecin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.patients*') ? 'active' : '' }}" href="{{ route('admin.medecin.patients') }}">
                        <i class="fas fa-users"></i>
                        Mes Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.dossiers*') ? 'active' : '' }}" href="{{ route('admin.medecin.dossiers') }}">
                        <i class="fas fa-file-medical"></i>
                        Dossiers Médicaux
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.rendezvous*') ? 'active' : '' }}" href="{{ route('admin.medecin.rendezvous') }}">
                        <i class="fas fa-calendar-alt"></i>
                        Mes Rendez-vous
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Déconnexion
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(auth()->user()->nom, 0, 1) }}
                </div>
                <div class="user-details">
                    <div class="name">{{ auth()->user()->nom }}</div>
                    <div class="role">Médecin</div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
