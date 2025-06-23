// Fonction pour ouvrir la modale de paiement
function openPaymentModal(planId, amount) {
    document.getElementById('amount').value = amount + ' FCFA';
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    paymentModal.show();
}

// Fonction pour traiter le paiement
function processPayment() {
    const phoneNumber = document.getElementById('phoneNumber').value;
    const email = document.getElementById('email').value;
    const amount = document.getElementById('amount').value;

    if (!phoneNumber || !email) {
        alert('Veuillez remplir tous les champs requis');
        return;
    }

    // Fermer la modale de paiement
    const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
    paymentModal.hide();

    // Ouvrir la modale de mot de passe Orange
    const orangePasswordModal = new bootstrap.Modal(document.getElementById('orangePasswordModal'));
    orangePasswordModal.show();
}

// Fonction pour confirmer le paiement
function confirmPayment() {
    const orangePassword = document.getElementById('orangePassword').value;

    if (!orangePassword) {
        alert('Veuillez entrer votre mot de passe Orange Money');
        return;
    }

    // Ici, vous pouvez ajouter la logique pour envoyer les données au serveur
    // et traiter le paiement

    // Fermer la modale de mot de passe
    const orangePasswordModal = bootstrap.Modal.getInstance(document.getElementById('orangePasswordModal'));
    orangePasswordModal.hide();

    // Afficher un message de succès
    alert('Paiement effectué avec succès !');
} 