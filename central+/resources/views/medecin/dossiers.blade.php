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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Dossier Médical</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createDossierForm">
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
                        <label class="form-label">Date de Consultation <span class="text-danger">*</span></label>
                        <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diagnostic <span class="text-danger">*</span></label>
                        <textarea name="diagnostic" class="form-control" rows="3" required placeholder="Décrivez le diagnostic..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Traitement <span class="text-danger">*</span></label>
                        <textarea name="traitement" class="form-control" rows="3" required placeholder="Décrivez le traitement prescrit..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="2" placeholder="Observations supplémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le Dossier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
