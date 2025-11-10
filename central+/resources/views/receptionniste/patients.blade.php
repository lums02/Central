@extends('layouts.admin')

@section('title', 'Gestion des Patients')
@section('page-title', 'Gestion des Patients')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users text-primary me-2"></i>Gestion des Patients
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauPatientModal">
            <i class="fas fa-user-plus me-2"></i>Nouveau Patient
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Liste des patients -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des Patients ({{ $patients->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nom Complet</th>
                            <th>Date Naissance</th>
                            <th>Sexe</th>
                            <th>Téléphone</th>
                            <th>Groupe Sanguin</th>
                            <th>Date Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td class="font-weight-bold">{{ $patient->nom }} {{ $patient->prenom ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($patient->sexe) }}</td>
                            <td>{{ $patient->telephone }}</td>
                            <td>
                                @if($patient->groupe_sanguin)
                                <span class="badge bg-danger">{{ $patient->groupe_sanguin }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Modal Modifier Patient -->
                        <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-edit me-2"></i>Modifier Patient
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.receptionniste.patients.update', $patient->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nom</label>
                                                    <input type="text" name="nom" class="form-control" value="{{ $patient->nom }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Prénom</label>
                                                    <input type="text" name="prenom" class="form-control" value="{{ $patient->prenom }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Téléphone</label>
                                                <input type="tel" name="telephone" class="form-control" value="{{ $patient->telephone }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Adresse</label>
                                                <textarea name="adresse" class="form-control" rows="2" required>{{ $patient->adresse }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Groupe Sanguin</label>
                                                <select name="groupe_sanguin" class="form-control">
                                                    <option value="">-- Non renseigné --</option>
                                                    <option value="A+" {{ $patient->groupe_sanguin == 'A+' ? 'selected' : '' }}>A+</option>
                                                    <option value="A-" {{ $patient->groupe_sanguin == 'A-' ? 'selected' : '' }}>A-</option>
                                                    <option value="B+" {{ $patient->groupe_sanguin == 'B+' ? 'selected' : '' }}>B+</option>
                                                    <option value="B-" {{ $patient->groupe_sanguin == 'B-' ? 'selected' : '' }}>B-</option>
                                                    <option value="AB+" {{ $patient->groupe_sanguin == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                    <option value="AB-" {{ $patient->groupe_sanguin == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                    <option value="O+" {{ $patient->groupe_sanguin == 'O+' ? 'selected' : '' }}>O+</option>
                                                    <option value="O-" {{ $patient->groupe_sanguin == 'O-' ? 'selected' : '' }}>O-</option>
                                                </select>
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3 d-block" style="opacity: 0.2;"></i>
                                Aucun patient enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($patients->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $patients->links() }}
            </div>
            @endif
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
@endsection

