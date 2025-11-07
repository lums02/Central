@extends('layouts.medecin')

@section('page-title', 'Détails Dossier Médical')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <a href="{{ route('admin.medecin.dossiers') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour aux Dossiers
        </a>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-file-medical"></i> {{ $dossier->numero_dossier }}
                    <span class="badge bg-light text-primary ms-2">{{ ucfirst($dossier->statut) }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <label><i class="fas fa-calendar"></i> Date de Consultation</label>
                            <p>{{ $dossier->date_consultation->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <label><i class="fas fa-hospital"></i> Hôpital</label>
                            <p>{{ $dossier->hopital->nom ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @if($dossier->motif_consultation)
                <div class="medical-section">
                    <h6><i class="fas fa-notes-medical"></i> Motif de Consultation</h6>
                    <p>{{ $dossier->motif_consultation }}</p>
                </div>
                @endif

                <div class="medical-section">
                    <h6><i class="fas fa-stethoscope"></i> Diagnostic</h6>
                    <p>{{ $dossier->diagnostic }}</p>
                </div>

                <div class="medical-section">
                    <h6><i class="fas fa-pills"></i> Traitement Prescrit</h6>
                    <div class="prescription-box">
                        {{ $dossier->traitement }}
                    </div>
                </div>

                @if($dossier->observations)
                <div class="medical-section">
                    <h6><i class="fas fa-comment-medical"></i> Observations</h6>
                    <p>{!! nl2br(e($dossier->observations)) !!}</p>
                </div>
                @endif

                <div class="text-end mt-4">
                    <button class="btn btn-info me-2" onclick="prescrireExamen()">
                        <i class="fas fa-flask"></i> Prescrire des Examens
                    </button>
                    <button class="btn btn-success me-2" onclick="ajouterConsultation()">
                        <i class="fas fa-plus-circle"></i> Ajouter une Consultation
                    </button>
                    <button class="btn btn-warning" onclick="editDossier()">
                        <i class="fas fa-edit"></i> Modifier le Dossier
                    </button>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>
                    <i class="fas fa-clock"></i> Créé le {{ $dossier->created_at->format('d/m/Y à H:i') }}
                    @if($dossier->updated_at != $dossier->created_at)
                        • Modifié le {{ $dossier->updated_at->format('d/m/Y à H:i') }}
                    @endif
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Patient</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-large bg-info text-white mx-auto">
                        {{ substr($dossier->patient->nom, 0, 2) }}
                    </div>
                    <h5 class="mt-2">{{ $dossier->patient->nom }}</h5>
                </div>
                <hr>
                <div class="patient-info-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $dossier->patient->email }}</span>
                </div>
                <div class="patient-info-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $dossier->patient->telephone ?? 'Non renseigné' }}</span>
                </div>
                @if($dossier->patient->date_naissance)
                <div class="patient-info-item">
                    <i class="fas fa-birthday-cake"></i>
                    <span>{{ \Carbon\Carbon::parse($dossier->patient->date_naissance)->age }} ans</span>
                </div>
                @endif
                <div class="patient-info-item">
                    <i class="fas fa-venus-mars"></i>
                    <span>{{ ucfirst($dossier->patient->sexe ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Prescrire Examens -->
<div class="modal fade" id="prescrireExamenModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-flask me-2"></i>Prescrire des Examens Médicaux</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="prescrireExamenForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Patient :</strong> {{ $dossier->patient->nom }}<br>
                        <strong>Dossier N° :</strong> {{ $dossier->numero_dossier }}
                    </div>
                    
                    <div id="examensContainer">
                        <div class="examen-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type d'Examen <span class="text-danger">*</span></label>
                                    <select name="examens[0][type_examen]" class="form-select" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option value="biologique">Analyse Biologique</option>
                                        <option value="imagerie">Imagerie Médicale</option>
                                        <option value="fonctionnel">Examen Fonctionnel</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de l'Examen <span class="text-danger">*</span></label>
                                    <input type="text" name="examens[0][nom_examen]" class="form-control" required placeholder="Ex: NFS, Radio thorax, ECG">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Date de Prescription</label>
                                    <input type="date" name="examens[0][date_prescription]" class="form-control" value="{{ date('Y-m-d') }}">
                                    <small class="text-muted">Le prix sera fixé par le caissier</small>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Indication / Raison <span class="text-danger">*</span></label>
                                    <textarea name="examens[0][indication]" class="form-control" rows="2" required placeholder="Raison de cet examen..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="ajouterExamen()">
                        <i class="fas fa-plus"></i> Ajouter un autre examen
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-paper-plane me-2"></i>Prescrire les Examens
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajouter Consultation -->
<div class="modal fade" id="ajouterConsultationModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Ajouter une Consultation au Dossier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajouterConsultationForm">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="alert alert-info">
                        <strong>Patient :</strong> {{ $dossier->patient->nom }}<br>
                        <strong>Dossier N° :</strong> {{ $dossier->numero_dossier }}
                    </div>
                    
                    <!-- Section: Nouvelle Consultation -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-calendar me-2"></i>Informations de la Consultation</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de cette Consultation <span class="text-danger">*</span></label>
                                <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type de Consultation</label>
                                <select name="type_consultation" class="form-select">
                                    <option value="suivi">Consultation de Suivi</option>
                                    <option value="urgence">Consultation d'Urgence</option>
                                    <option value="controle">Consultation de Contrôle</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Consultation -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-stethoscope me-2"></i>Consultation</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Motif de la Consultation <span class="text-danger">*</span></label>
                                <textarea name="motif_consultation" class="form-control" rows="2" required placeholder="Raison de cette consultation..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Symptômes Actuels</label>
                                <textarea name="symptomes" class="form-control" rows="3" placeholder="Nouveaux symptômes ou évolution..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Examen Clinique</label>
                                <textarea name="examen_clinique" class="form-control" rows="3" placeholder="Tension, température, pouls, examen physique..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Diagnostic & Traitement -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-diagnoses me-2"></i>Diagnostic & Traitement</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Évolution / Nouveau Diagnostic</label>
                                <textarea name="diagnostic_evolution" class="form-control" rows="2" placeholder="Évolution du diagnostic ou nouveau diagnostic..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Ajustement du Traitement</label>
                                <textarea name="traitement_ajustement" class="form-control" rows="3" placeholder="Nouveaux médicaments ou ajustements des dosages..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Notes -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-notes-medical me-2"></i>Notes de Suivi</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Notes de cette Consultation <span class="text-danger">*</span></label>
                                <textarea name="notes_consultation" class="form-control" rows="4" required placeholder="Notes, observations, évolution de l'état du patient..."></textarea>
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
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Ajouter la Consultation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div class="modal fade" id="editDossierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDossierForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Diagnostic</label>
                        <textarea name="diagnostic" class="form-control" rows="3" required>{{ $dossier->diagnostic }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Traitement</label>
                        <textarea name="traitement" class="form-control" rows="3" required>{{ $dossier->traitement }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Observations</label>
                        <textarea name="observations" class="form-control" rows="2">{{ $dossier->observations }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
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
    border-left: 4px solid #28a745;
}

.section-title {
    color: #28a745;
    font-weight: 700;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}
</style>

<script>
let examenCount = 1;

function prescrireExamen() {
    new bootstrap.Modal(document.getElementById('prescrireExamenModal')).show();
}

function ajouterExamen() {
    const container = document.getElementById('examensContainer');
    const newExamen = `
        <div class="examen-item border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Type d'Examen <span class="text-danger">*</span></label>
                    <select name="examens[${examenCount}][type_examen]" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="biologique">Analyse Biologique</option>
                        <option value="imagerie">Imagerie Médicale</option>
                        <option value="fonctionnel">Examen Fonctionnel</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom de l'Examen <span class="text-danger">*</span></label>
                    <input type="text" name="examens[${examenCount}][nom_examen]" class="form-control" required placeholder="Ex: NFS, Radio thorax, ECG">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Date de Prescription</label>
                    <input type="date" name="examens[${examenCount}][date_prescription]" class="form-control" value="{{ date('Y-m-d') }}">
                    <small class="text-muted">Le prix sera fixé par le caissier</small>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Indication / Raison <span class="text-danger">*</span></label>
                    <textarea name="examens[${examenCount}][indication]" class="form-control" rows="2" required placeholder="Raison de cet examen..."></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newExamen);
    examenCount++;
}

function ajouterConsultation() {
    new bootstrap.Modal(document.getElementById('ajouterConsultationModal')).show();
}

function editDossier() {
    new bootstrap.Modal(document.getElementById('editDossierModal')).show();
}

// Prescrire examens
document.getElementById('prescrireExamenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("admin.medecin.examens.prescrire", $dossier->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Examens prescrits avec succès ! Le caissier sera notifié.');
            location.reload();
        } else {
            alert('Erreur: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la prescription des examens');
    });
});

// Soumettre nouvelle consultation
document.getElementById('ajouterConsultationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // Formater les données pour les ajouter aux observations
    const dateConsult = formData.get('date_consultation');
    const motif = formData.get('motif_consultation');
    const symptomes = formData.get('symptomes');
    const examen = formData.get('examen_clinique');
    const diagnostic = formData.get('diagnostic_evolution');
    const traitement = formData.get('traitement_ajustement');
    const notes = formData.get('notes_consultation');
    const urgence = formData.get('urgence');
    
    // Construire le texte de la nouvelle consultation
    let nouvelleConsultation = '\n\n=== CONSULTATION DU ' + dateConsult + ' ===\n';
    if (motif) nouvelleConsultation += 'Motif: ' + motif + '\n';
    if (symptomes) nouvelleConsultation += 'Symptômes: ' + symptomes + '\n';
    if (examen) nouvelleConsultation += 'Examen clinique: ' + examen + '\n';
    if (diagnostic) nouvelleConsultation += 'Diagnostic/Évolution: ' + diagnostic + '\n';
    if (traitement) nouvelleConsultation += 'Traitement: ' + traitement + '\n';
    if (notes) nouvelleConsultation += 'Notes: ' + notes + '\n';
    nouvelleConsultation += 'Urgence: ' + urgence + '\n';
    
    // Ajouter aux observations existantes
    const updateData = new FormData();
    updateData.append('diagnostic', '{{ $dossier->diagnostic }}');
    updateData.append('traitement', '{{ $dossier->traitement }}');
    updateData.append('observations', '{{ $dossier->observations }}' + nouvelleConsultation);
    
    fetch('{{ route("admin.medecin.dossier.update", $dossier->id) }}', {
        method: 'POST',
        body: updateData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Consultation ajoutée avec succès !');
            location.reload();
        } else {
            alert('Erreur: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout de la consultation');
    });
});

// Modifier dossier
document.getElementById('editDossierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("admin.medecin.dossier.update", $dossier->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
});
</script>

<style>
.avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
}

.info-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--central-primary);
}

.info-box label {
    display: block;
    font-weight: 600;
    color: var(--central-primary);
    margin-bottom: 5px;
}

.medical-section {
    margin-bottom: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.medical-section h6 {
    color: var(--central-primary);
    font-weight: 700;
    margin-bottom: 15px;
}

.prescription-box {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
    white-space: pre-wrap;
}

.patient-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6;
}

.patient-info-item:last-child {
    border-bottom: none;
}

.patient-info-item i {
    color: var(--central-primary);
    width: 20px;
}
</style>
@endsection

