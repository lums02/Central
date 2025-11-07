@extends('layouts.medecin')

@section('page-title', 'Mes Rendez-vous')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Mes Rendez-vous</h5>
                <button class="btn btn-primary" onclick="showCreateRendezVousModal()">
                    <i class="fas fa-plus me-2"></i>Nouveau Rendez-vous
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Heure</th>
                                <th>Patient</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rendezvous as $rdv)
                            <tr>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($rdv->date_rendezvous)->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ substr($rdv->heure_rendezvous, 0, 5) }}</small>
                                </td>
                                <td>{{ $rdv->patient->nom ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $rdv->type_consultation)) }}</td>
                                <td>
                                    <span class="badge bg-{{ $rdv->statut_color }}">{{ $rdv->statut_format }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($rdv->statut == 'en_attente')
                                            <button class="btn btn-sm btn-success" onclick="confirmerRendezVous({{ $rdv->id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-info" onclick="voirRendezVous({{ $rdv->id }}, '{{ $rdv->patient->nom }}', '{{ $rdv->date_rendezvous }}', '{{ $rdv->heure_rendezvous }}', '{{ $rdv->motif }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($rdv->statut != 'termine')
                                            <button class="btn btn-sm btn-danger" onclick="annulerRendezVous({{ $rdv->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun rendez-vous trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau rendez-vous -->
<div class="modal fade" id="createRendezVousModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRendezVousForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">Sélectionner un patient</option>
                            @foreach($patients ?? [] as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->nom }} ({{ $patient->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date_rendezvous" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heure <span class="text-danger">*</span></label>
                        <input type="time" name="heure_rendezvous" class="form-control" value="09:00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type de Rendez-vous *</label>
                        <select name="type_rendezvous" class="form-select" required>
                            <option value="consultation_generale">Consultation Générale</option>
                            <option value="consultation_specialisee">Consultation Spécialisée</option>
                            <option value="controle">Contrôle</option>
                            <option value="urgence">Urgence</option>
                            <option value="suivi">Suivi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif *</label>
                        <textarea name="motif" class="form-control" rows="2" required placeholder="Motif du rendez-vous..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Notes supplémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le Rendez-vous</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showCreateRendezVousModal() {
    new bootstrap.Modal(document.getElementById('createRendezVousModal')).show();
}

function confirmerRendezVous(rdvId) {
    if (confirm('Confirmer ce rendez-vous ?')) {
        updateStatut(rdvId, 'confirme');
    }
}

function voirRendezVous(id, patient, date, heure, motif) {
    alert(`Rendez-vous #${id}\nPatient: ${patient}\nDate: ${date} à ${heure}\nMotif: ${motif}`);
}

function modifierRendezVous(rdvId) {
    alert('Fonctionnalité de modification à venir');
}

function annulerRendezVous(rdvId) {
    if (confirm('Annuler ce rendez-vous ?')) {
        updateStatut(rdvId, 'annule');
    }
}

function updateStatut(rdvId, statut) {
    fetch(`/admin/medecin/rendezvous/${rdvId}/statut`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ statut: statut })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

document.getElementById('createRendezVousForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.medecin.rendezvous.create") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Rendez-vous créé avec succès !');
            location.reload();
        } else {
            alert('Erreur lors de la création du rendez-vous: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création du rendez-vous');
    });
});
</script>
@endsection
