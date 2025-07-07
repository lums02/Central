 <!-- Modal de paiement -->
 <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Paiement de l'abonnement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Méthodes de paiement</h6>
                            <div class="payment-method" onclick="selectPayment('card')">
                                <i class="fas fa-credit-card payment-icon"></i>
                                <span>Carte bancaire</span>
                            </div>
                            <div class="payment-method" onclick="selectPayment('mobile')">
                                <i class="fas fa-mobile-alt payment-icon"></i>
                                <span>Mobile Money</span>
                            </div>
                            <div class="payment-method" onclick="selectPayment('orange')">
                                <i class="fas fa-wallet payment-icon"></i>
                                <span>Orange Money</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Récapitulatif</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h5 id="planName">Plan Mensuel</h5>
                                    <p id="planPrice">4.99$/mois</p>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Total</span>
                                        <strong id="totalPrice">4.99$</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaires de paiement -->
                    <div class="mt-4">
                        <!-- Carte bancaire -->
                        <div id="cardForm" class="payment-form">
                            <div class="mb-3">
                                <label class="form-label">Numéro de carte</label>
                                <input type="text" class="form-control" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Date d'expiration</label>
                                        <input type="text" class="form-control" placeholder="MM/AA">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="text" class="form-control" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Money -->
                        <div id="mobileForm" class="payment-form" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Numéro de téléphone</label>
                                <input type="tel" class="form-control" placeholder="+225 07 12 34 56 78">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Opérateur</label>
                                <select class="form-control">
                                    <option value="">Sélectionner un opérateur</option>
                                    <option value="mtn">MTN Mobile Money</option>
                                    <option value="moov">Moov Money</option>
                                    <option value="wave">Wave</option>
                                </select>
                            </div>
                        </div>

                        <!-- Orange Money -->
                        <div id="orangeForm" class="payment-form" style="display: none;">
                            <div class="orange-money-form">
                                <div class="orange-money-header">
                                    <div class="orange-money-logo">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <h5>Paiement Orange Money</h5>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Numéro Orange Money</label>
                                    <input type="tel" class="form-control" id="orangeNumber" placeholder="+225 07 12 34 56 78">
                                    <small class="text-muted">Entrez votre numéro Orange Money enregistré</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Montant</label>
                                    <input type="text" class="form-control" id="orangeAmount" readonly>
                                    <small class="text-muted">Montant à débiter de votre compte Orange Money</small>
                                </div>
                                <button class="btn btn-warning w-100" onclick="requestOrangePayment()">
                                    Continuer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal du code secret Orange Money -->
    <div class="modal fade" id="orangePasswordModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-lock me-2"></i>
                        Code secret
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="orange-money-logo mb-3">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h6>Entrez votre code secret</h6>
                        <p class="text-muted small">Pour valider la transaction</p>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control form-control-lg text-center" 
                               id="orangePassword" maxlength="4" placeholder="****"
                               style="letter-spacing: 8px; font-size: 24px;">
                        <small class="text-muted d-block text-center mt-2">Code secret à 4 chiffres</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-warning" onclick="confirmOrangePayment()">
                        Valider
                    </button>
                </div>
            </div>
        </div>
    </div>