@extends('layouts.medecin')

@section('page-title', 'Dashboard Médecin')

@section('content')

<!-- Statistiques -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <h3>{{ $stats['total_patients'] }}</h3>
            <p><i class="fas fa-users me-2"></i>Total Patients</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <h3>{{ $stats['total_dossiers'] }}</h3>
            <p><i class="fas fa-file-medical me-2"></i>Dossiers Médicaux</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <h3>{{ $stats['dossiers_actifs'] }}</h3>
            <p><i class="fas fa-check-circle me-2"></i>Dossiers Actifs</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <h3>{{ $stats['consultations_aujourd_hui'] }}</h3>
            <p><i class="fas fa-calendar-day me-2"></i>Consultations Aujourd'hui</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Patients récents -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Mes Patients</h5>
            </div>
            <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Dernière Consultation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients->take(5) as $patient)
                                <tr>
                                    <td>{{ $patient->nom }}</td>
                                    <td>{{ $patient->email }}</td>
                                    <td>
                                        @php
                                            $dernierDossier = $patient->dossiers()->where('medecin_id', auth()->id())->latest()->first();
                                        @endphp
                                        {{ $dernierDossier ? $dernierDossier->date_consultation->format('d/m/Y') : 'Aucune' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.medecin.dossiers') }}?patient={{ $patient->id }}" class="btn btn-sm btn-primary">Voir Dossiers</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun patient trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.medecin.patients') }}" class="btn btn-primary">Voir Tous les Patients</a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Dossiers récents -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Dossiers Médicaux Récents</h5>
            </div>
            <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>N° Dossier</th>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dossiers->take(5) as $dossier)
                                <tr>
                                    <td>{{ $dossier->numero_dossier }}</td>
                                    <td>{{ $dossier->patient->nom }}</td>
                                    <td>{{ $dossier->date_consultation->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.medecin.dossier.show', $dossier->id) }}" class="btn btn-sm btn-info">Voir</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun dossier trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.medecin.dossiers') }}" class="btn btn-primary">Voir Tous les Dossiers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
