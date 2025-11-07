@extends('layouts.medecin')

@section('page-title', 'Dashboard Médecin')

@section('content')

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card stats-card-blue">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-info">
                <h3>{{ $stats['total_patients'] }}</h3>
                <p>Total Patients</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card stats-card-green">
            <div class="stats-icon">
                <i class="fas fa-file-medical"></i>
            </div>
            <div class="stats-info">
                <h3>{{ $stats['total_dossiers'] }}</h3>
                <p>Dossiers Médicaux</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card stats-card-orange">
            <div class="stats-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-info">
                <h3>{{ $stats['dossiers_actifs'] }}</h3>
                <p>Dossiers Actifs</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card stats-card-purple">
            <div class="stats-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stats-info">
                <h3>{{ $stats['consultations_aujourd_hui'] }}</h3>
                <p>Consultations Aujourd'hui</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Patients récents -->
    <div class="col-xl-6 mb-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <div>
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Mes Patients Récents</h5>
                    <small class="text-muted">{{ $patients->take(5)->count() }} patients</small>
                </div>
                <a href="{{ route('admin.medecin.patients') }}" class="btn btn-sm btn-outline-primary">
                    Voir Tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-card-body">
                @forelse($patients->take(5) as $patient)
                    <div class="patient-item">
                        <div class="patient-avatar">
                            {{ substr($patient->nom, 0, 1) }}
                        </div>
                        <div class="patient-info">
                            <h6 class="mb-0">{{ $patient->nom }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>{{ $patient->email }}
                            </small>
                        </div>
                        <div class="patient-meta">
                            @php
                                $dernierDossier = $patient->dossiers()->where('medecin_id', auth()->id())->latest()->first();
                            @endphp
                            <small class="text-muted d-block">Dernière consultation</small>
                            <strong>{{ $dernierDossier ? $dernierDossier->date_consultation->format('d/m/Y') : 'Aucune' }}</strong>
                        </div>
                        <div class="patient-actions">
                            <a href="{{ route('admin.medecin.dossiers') }}?patient={{ $patient->id }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-folder-open"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                        <p>Aucun patient trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Dossiers récents -->
    <div class="col-xl-6 mb-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <div>
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Dossiers Récents</h5>
                    <small class="text-muted">{{ $dossiers->take(5)->count() }} dossiers</small>
                </div>
                <a href="{{ route('admin.medecin.dossiers') }}" class="btn btn-sm btn-outline-primary">
                    Voir Tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-card-body">
                @forelse($dossiers->take(5) as $dossier)
                    <div class="dossier-item">
                        <div class="dossier-icon">
                            <i class="fas fa-file-medical-alt"></i>
                        </div>
                        <div class="dossier-info">
                            <h6 class="mb-0">{{ $dossier->patient->nom }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-hashtag me-1"></i>{{ $dossier->numero_dossier }}
                            </small>
                        </div>
                        <div class="dossier-meta">
                            <span class="badge bg-success">{{ $dossier->statut }}</span>
                            <small class="text-muted d-block mt-1">{{ $dossier->date_consultation->format('d/m/Y') }}</small>
                        </div>
                        <div class="dossier-actions">
                            <a href="{{ route('admin.medecin.dossier.show', $dossier->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                        <p>Aucun dossier trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animer les cartes de stats
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endsection
