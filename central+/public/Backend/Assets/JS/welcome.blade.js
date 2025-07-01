
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        let selectedPlan = '';

        function startTrial() {
            Swal.fire({
                title: 'Commencer votre essai gratuit',
                html: 'Profitez de 30 jours gratuits pour tester toutes nos fonctionnalités !',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Commencer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'register.php';
                }
            });
        }

        function selectPlan(plan) {
            selectedPlan = plan;
            const planName = document.getElementById('planName');
            const planPrice = document.getElementById('planPrice');
            const totalPrice = document.getElementById('totalPrice');

            if (plan === 'monthly') {
                planName.textContent = 'Plan Mensuel';
                planPrice.textContent = '4.99$/mois';
                totalPrice.textContent = '4.99$';
            } else {
                planName.textContent = 'Plan Annuel';
                planPrice.textContent = '49$/an';
                totalPrice.textContent = '49$';
            }
            paymentModal.show();
        }

        function selectPayment(method) {
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');

            document.querySelectorAll('.payment-form').forEach(form => {
                form.style.display = 'none';
            });
            document.getElementById(method + 'Form').style.display = 'block';

            if (method === 'orange') {
                const amount = selectedPlan === 'monthly' ? '4.99' : '4.99';
                document.getElementById('orangeAmount').value = amount + ' $';
            }
        }

        function requestOrangePayment() {
            const orangeNumber = document.getElementById('orangeNumber').value;

            if (!orangeNumber) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Veuillez entrer votre numéro Orange Money'
                });
                return;
            }

            // Fermer le modal de paiement
            paymentModal.hide();

            // Afficher le modal de mot de passe
            const orangePasswordModal = new bootstrap.Modal(document.getElementById('orangePasswordModal'));
            orangePasswordModal.show();

            // Focus sur le champ de mot de passe
            setTimeout(() => {
                document.getElementById('orangePassword').focus();
            }, 500);
        }

        function confirmOrangePayment() {
            const password = document.getElementById('orangePassword').value;
            const orangeNumber = document.getElementById('orangeNumber').value;
            const amount = document.getElementById('orangeAmount').value;

            if (password.length !== 4) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Le code secret doit contenir 4 chiffres'
                });
                return;
            }

            // Fermer le modal de mot de passe
            bootstrap.Modal.getInstance(document.getElementById('orangePasswordModal')).hide();

            // Afficher la demande de confirmation
            Swal.fire({
                title: 'Confirmer la transaction',
                html: `
                    <div class="text-center">
                        <div class="orange-money-logo mb-3">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <p>Voulez-vous confirmer le paiement de <strong>${amount}</strong> ?</p>
                        <p class="text-muted">Numéro: ${orangeNumber}</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#ff6b00',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simuler le traitement
                    Swal.fire({
                        title: 'Traitement en cours',
                        html: `
                            <div class="text-center">
                                <div class="spinner-border text-warning mb-3" role="status"></div>
                                <p>Veuillez patienter pendant que nous traitons votre paiement...</p>
                            </div>
                        `,
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });

                    // Simuler un délai de traitement
                    setTimeout(() => {
                        showOrangeReceipt(orangeNumber, amount);
                    }, 3000);
                }
            });
        }

        function showOrangeReceipt(number, amount) {
            const date = new Date().toLocaleString();
            const transactionId = 'OM' + Math.random().toString(36).substr(2, 9).toUpperCase();

            Swal.fire({
                title: 'Paiement réussi !',
                html: `
                    <div class="orange-money-receipt">
                        <div class="receipt-header">
                            <div class="receipt-logo">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <h5>Orange Money</h5>
                            <p class="text-success">Transaction réussie</p>
                        </div>
                        <div class="receipt-details">
                            <div class="receipt-row">
                                <span>Numéro</span>
                                <strong>${number}</strong>
                            </div>
                            <div class="receipt-row">
                                <span>Montant</span>
                                <strong>${amount}</strong>
                            </div>
                            <div class="receipt-row">
                                <span>Date</span>
                                <strong>${date}</strong>
                            </div>
                            <div class="receipt-row">
                                <span>Transaction ID</span>
                                <strong>${transactionId}</strong>
                            </div>
                        </div>
                        <div class="receipt-footer">
                            <p class="mb-1">Un SMS de confirmation a été envoyé à votre numéro</p>
                            <small class="text-muted">Conservez ce reçu comme preuve de paiement</small>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Accéder à mon espace',
                allowOutsideClick: false,
                confirmButtonColor: '#28a745'
            }).then(() => {
                window.location.href = 'register.php';
            });
        }