// Fonction pour démarrer l'essai gratuit
function startTrial() {
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    document.getElementById('amount').value = '0$';
    paymentModal.show();
}

// Fonction pour sélectionner un plan
function selectPlan(planType) {
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const amount = planType === 'monthly' ? '4.99$' : '49$';
    document.getElementById('amount').value = amount;
    paymentModal.show();
}

// Fonction pour traiter le paiement
function processPayment() {
    const phoneNumber = document.getElementById('phoneNumber').value;
    const email = document.getElementById('email').value;
    const amount = document.getElementById('amount').value;

    if (!phoneNumber || !email) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez remplir tous les champs requis'
        });
        return;
    }

    // Fermer le modal de paiement
    const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
    paymentModal.hide();

    // Afficher le modal de mot de passe Orange
    const orangePasswordModal = new bootstrap.Modal(document.getElementById('orangePasswordModal'));
    orangePasswordModal.show();
}

// Fonction pour confirmer le paiement
function confirmPayment() {
    const password = document.getElementById('orangePassword').value;

    if (!password) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez entrer votre mot de passe Orange Money'
        });
        return;
    }

    // Fermer le modal de mot de passe
    const orangePasswordModal = bootstrap.Modal.getInstance(document.getElementById('orangePasswordModal'));
    orangePasswordModal.hide();

    // Afficher le spinner de chargement
    const spinnerOverlay = document.createElement('div');
    spinnerOverlay.className = 'spinner-overlay';
    spinnerOverlay.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(spinnerOverlay);

    // Simuler un traitement de paiement
    setTimeout(() => {
        // Supprimer le spinner
        document.body.removeChild(spinnerOverlay);

        // Afficher le reçu
        Swal.fire({
            icon: 'success',
            title: 'Paiement réussi !',
            html: `
                <div class="orange-money-receipt">
                    <div class="receipt-header">
                        <div class="receipt-logo">
                            <i class="fas fa-check"></i>
                        </div>
                        <h4>Transaction Réussie</h4>
                    </div>
                    <div class="receipt-details">
                        <div class="receipt-row">
                            <span>Montant</span>
                            <strong>${document.getElementById('amount').value}</strong>
                        </div>
                        <div class="receipt-row">
                            <span>Numéro</span>
                            <strong>${document.getElementById('phoneNumber').value}</strong>
                        </div>
                        <div class="receipt-row">
                            <span>Email</span>
                            <strong>${document.getElementById('email').value}</strong>
                        </div>
                    </div>
                    <div class="receipt-footer">
                        Merci d'avoir choisi CENTRAL+
                    </div>
                </div>
            `,
            confirmButtonText: 'Fermer'
        });
    }, 2000);
} 