@extends('layouts.admin')

@section('title', 'Dashboard Réceptionniste')
@section('page-title', 'Dashboard Réceptionniste')

@section('content')
<div class="container-fluid">
    <!-- Actions rapides -->
    <div class="card shadow mb-4" style="border: 1px solid #d1e7fd; border-radius: 8px;">
        <div class="card-header py-3" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-bolt me-2"></i>Actions Rapides
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <button class="btn btn-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#nouveauPatientModal">
                        <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                        Nouveau Patient
                    </button>
                </div>
                <div class="col-md-4 mb-3">
                    <button class="btn btn-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#nouveauRdvModal">
                        <i class="fas fa-calendar-plus fa-2x d-block mb-2"></i>
                        Nouveau Rendez-vous
                    </button>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.receptionniste.rendezvous') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                        Voir Planning
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques du jour -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                Patients du Jour
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['patients_du_jour'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-calendar-day text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                RDV du Jour
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['rdv_du_jour'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                RDV Confirmés
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['rdv_confirmes'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                En Attente
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['rdv_en_attente'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Rendez-vous du jour -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100" style="border: 1px solid #d1e7fd; border-radius: 8px;">
                <div class="card-header py-3" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day me-2"></i>Rendez-vous d'Aujourd'hui
                    </h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @forelse($rendezVousDuJour as $rdv)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">{{ substr($rdv->heure_rendezvous, 0, 5) }}</h6>
                                <div class="text-muted small mb-1">
                                    <i class="fas fa-user me-1"></i>{{ $rdv->patient->nom }} {{ $rdv->patient->prenom ?? '' }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-user-md me-1"></i>Dr. {{ $rdv->medecin->nom }}
                                </div>
                                <p class="mb-0 text-muted small">{{ $rdv->motif }}</p>
                            </div>
                            <div class="ms-3">
                                <span class="badge bg-{{ $rdv->statut == 'confirme' ? 'success' : 'warning' }}">
                                    {{ ucfirst($rdv->statut) }}
                                </span>
                                @if($rdv->statut == 'en_attente')
                                <form action="{{ route('admin.receptionniste.rendezvous.confirmer', $rdv->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success ms-2">
                                        <i class="fas fa-check"></i> Confirmer
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.2;"></i>
                        <p class="mb-0">Aucun rendez-vous aujourd'hui</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Patients récents -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100" style="border: 1px solid #d1e7fd; border-radius: 8px;">
                <div class="card-header py-3" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Patients Récents
                    </h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @forelse($patientsRecents as $patient)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <h6 class="mb-1 text-dark">{{ $patient->nom }} {{ $patient->prenom ?? '' }}</h6>
                        <small class="text-muted d-block">
                            <i class="fas fa-phone me-1"></i>{{ $patient->telephone }}
                        </small>
                        <small class="text-muted d-block">
                            <i class="fas fa-calendar me-1"></i>Inscrit le {{ $patient->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-3x mb-3" style="opacity: 0.2;"></i>
                        <p class="mb-0">Aucun patient récent</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Patient -->
<div class="modal fade" id="nouveauPatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Nouveau Patient
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.receptionniste.patients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de Naissance *</label>
                            <input type="date" name="date_naissance" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sexe *</label>
                            <select name="sexe" class="form-control" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="masculin">Masculin</option>
                                <option value="feminin">Féminin</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone *</label>
                            <input type="tel" name="telephone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse *</label>
                        <textarea name="adresse" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Groupe Sanguin</label>
                            <select name="groupe_sanguin" class="form-control">
                                <option value="">-- Non renseigné --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de Passe *</label>
                            <input type="password" name="mot_de_passe" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouveau Rendez-vous -->
<div class="modal fade" id="nouveauRdvModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>Nouveau Rendez-vous
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.receptionniste.rendezvous.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pour créer un rendez-vous, le patient doit déjà être enregistré. Si c'est un nouveau patient, créez-le d'abord.
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient *</label>
                            <select name="patient_id" class="form-control" required>
                                <option value="">-- Recherchez un patient --</option>
                                @foreach($patientsRecents as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->nom }} {{ $patient->prenom ?? '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Médecin *</label>
                            <select name="medecin_id" class="form-control" required>
                                <option value="">-- Sélectionnez un médecin --</option>
                                @php
                                    $medecins = \App\Models\Utilisateur::where('entite_id', auth()->user()->entite_id)
                                        ->where('type_utilisateur', 'hopital')
                                        ->where('role', 'medecin')
                                        ->get();
                                @endphp
                                @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date_rendezvous" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure *</label>
                            <input type="time" name="heure_rendezvous" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif de Consultation *</label>
                        <textarea name="motif" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le Rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

