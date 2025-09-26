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
                            @forelse($rendezvous ?? [] as $rdv)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $rdv->date_rendezvous->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $rdv->heure_rendezvous }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-primary rounded-circle">
                                                {{ substr($rdv->patient->nom, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $rdv->patient->nom }}</h6>
                                            <small class="text-muted">{{ $rdv->patient->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $rdv->type_rendezvous ?? 'Consultation' }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $rdv->statut === 'confirme' ? 'bg-success' : ($rdv->statut === 'en_attente' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ ucfirst($rdv->statut ?? 'En attente') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-success" onclick="confirmerRendezVous({{ $rdv->id }})">
                                            <i class="fas fa-check"></i> Confirmer
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="modifierRendezVous({{ $rdv->id }})">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="annulerRendezVous({{ $rdv->id }})">
                                            <i class="fas fa-times"></i> Annuler
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                        <p>Aucun rendez-vous trouvé</p>
                                        <button class="btn btn-primary" onclick="showCreateRendezVousModal()">
                                            <i class="fas fa-plus me-2"></i>Créer le premier rendez-vous
                                        </button>
                                    </div>
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
                        <label class="form-label">Type de Rendez-vous</label>
                        <select name="type_rendezvous" class="form-select">
                            <option value="consultation">Consultation</option>
                            <option value="controle">Contrôle</option>
                            <option value="urgence">Urgence</option>
                            <option value="suivi">Suivi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Notes sur le rendez-vous..."></textarea>
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
        // TODO: Implémenter la confirmation
        alert('Fonctionnalité de confirmation à implémenter');
    }
}

function modifierRendezVous(rdvId) {
    // TODO: Implémenter la modification
    alert('Fonctionnalité de modification à implémenter');
}

function annulerRendezVous(rdvId) {
    if (confirm('Annuler ce rendez-vous ?')) {
        // TODO: Implémenter l'annulation
        alert('Fonctionnalité d\'annulation à implémenter');
    }
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
