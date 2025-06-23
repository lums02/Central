<!-- Modal de Paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Paiement via Orange Money</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Numéro Orange Money</label>
                        <input type="tel" class="form-control" id="phoneNumber" placeholder="Ex: 0999999999" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant à payer</label>
                        <input type="text" class="form-control" id="amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="votre@email.com" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="processPayment()">Payer</button>
            </div>
        </div>
    </div>
</div> 