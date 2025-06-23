<!-- Modal de Mot de Passe Orange -->
<div class="modal fade" id="orangePasswordModal" tabindex="-1" aria-labelledby="orangePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orangePasswordModalLabel">Confirmation Orange Money</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orangePasswordForm">
                    <div class="mb-3">
                        <label for="orangePassword" class="form-label">Mot de passe Orange Money</label>
                        <input type="password" class="form-control" id="orangePassword" required>
                        <div class="form-text">Entrez votre mot de passe Orange Money pour confirmer la transaction</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="confirmPayment()">Confirmer</button>
            </div>
        </div>
    </div>
</div> 