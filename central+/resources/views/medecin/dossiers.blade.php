@extends('layouts.medecin')

@section('page-title', 'Dossiers Médicaux')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Dossiers Médicaux</h5>
                <button class="btn btn-primary" onclick="showCreateDossierModal()">
                    <i class="fas fa-plus me-2"></i>Nouveau Dossier
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Dossier</th>
                                <th>Patient</th>
                                <th>Date Consultation</th>
                                <th>Diagnostic</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossiers as $dossier)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $dossier->numero_dossier }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-success rounded-circle">
                                                {{ substr($dossier->patient->nom, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $dossier->patient->nom }}</h6>
                                            <small class="text-muted">{{ $dossier->patient->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $dossier->date_consultation->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($dossier->diagnostic, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $dossier->statut === 'actif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($dossier->statut) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.medecin.dossier.show', $dossier->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <button class="btn btn-sm btn-warning" onclick="editDossier({{ $dossier->id }})">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-medical fa-3x mb-3"></i>
                                        <p>Aucun dossier médical trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($dossiers->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $dossiers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau dossier -->
<div class="modal fade" id="createDossierModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file-medical me-2"></i>Nouveau Dossier Médical Complet</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createDossierForm">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    
                    <!-- Section 1: Informations Patient -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-user me-2"></i>Informations Patient</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Patient <span class="text-danger">*</span></label>
                                <select name="patient_id" class="form-select" required>
                                    <option value="">-- Sélectionner un patient --</option>
                                    @foreach($patients ?? [] as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->nom }} - {{ $patient->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de Consultation <span class="text-danger">*</span></label>
                                <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Consultation -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-stethoscope me-2"></i>Consultation</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Motif de Consultation <span class="text-danger">*</span></label>
                                <textarea name="motif_consultation" class="form-control" rows="2" required placeholder="Ex: Douleurs abdominales, Fièvre persistante..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Symptômes Présentés</label>
                                <textarea name="symptomes" class="form-control" rows="3" placeholder="Décrivez les symptômes observés..."></textarea>
                                <small class="text-muted">Symptômes rapportés par le patient</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Examen Clinique</label>
                                <textarea name="examen_clinique" class="form-control" rows="3" placeholder="Tension, température, pouls, examen physique..."></textarea>
                                <small class="text-muted">Signes vitaux et observations cliniques</small>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Diagnostic & Traitement -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-diagnoses me-2"></i>Diagnostic & Traitement</h6>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Diagnostic Principal <span class="text-danger">*</span></label>
                                <textarea name="diagnostic" class="form-control" rows="2" required placeholder="Diagnostic principal..."></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Code CIM-10</label>
                                <input type="text" name="code_cim10" class="form-control" placeholder="Ex: J06.9">
                                <small class="text-muted">Classification</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Diagnostics Secondaires</label>
                                <textarea name="diagnostic_secondaire" class="form-control" rows="2" placeholder="Autres diagnostics associés..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Traitement Prescrit <span class="text-danger">*</span></label>
                                <textarea name="traitement" class="form-control" rows="4" required placeholder="Médicaments, dosages, durée...&#10;Ex: Paracétamol 500mg, 3x/jour, 7 jours"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Plan de Traitement</label>
                                <textarea name="plan_traitement" class="form-control" rows="2" placeholder="Plan thérapeutique à long terme..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Notes & Observations -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-notes-medical me-2"></i>Notes & Observations</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Recommandations</label>
                                <textarea name="recommandations" class="form-control" rows="2" placeholder="Recommandations diététiques, repos, activité physique..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Observations Médicales</label>
                                <textarea name="observations" class="form-control" rows="2" placeholder="Notes supplémentaires, évolution attendue..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prochain Rendez-vous</label>
                                <input type="date" name="date_prochain_rdv" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Niveau d'Urgence</label>
                                <select name="urgence" class="form-select">
                                    <option value="normale">Normale</option>
                                    <option value="urgente">Urgente</option>
                                    <option value="tres_urgente">Très Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le Dossier Médical
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.form-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 4px solid var(--central-primary);
}

.section-title {
    color: var(--central-primary);
    font-weight: 700;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}
</style>

<script>
function showCreateDossierModal() {
    new bootstrap.Modal(document.getElementById('createDossierModal')).show();
}

function editDossier(dossierId) {
    // TODO: Implémenter l'édition des dossiers
    alert('Fonctionnalité d\'édition à implémenter');
}

document.getElementById('createDossierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.medecin.dossier.create") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Dossier médical créé avec succès !');
            location.reload();
        } else {
            alert('Erreur lors de la création du dossier: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création du dossier');
    });
});
</script>
@endsection
