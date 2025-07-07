<div id="pricing" class="container py-5">
    <h2 class="text-center mb-5">Choisissez votre plan</h2>
    
    <div class="row">
        <!-- Plan Gratuit -->
        <div class="col-md-4">
            <div class="pricing-card">
                <div class="trial-badge">Essai gratuit</div>
                <h3>Plan Découverte</h3>
                <div class="display-4 my-4">Gratuit</div>
                <p class="text-muted">Pendant 30 jours</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Accès à toutes les fonctionnalités</li>
                    <li><i class="fas fa-check"></i> Gestion des patients</li>
                    <li><i class="fas fa-check"></i> Gestion des rendez-vous</li>
                    <li><i class="fas fa-check"></i> Support par email</li>
                </ul>
                <button class="btn btn-outline-primary w-100 mt-4" onclick="startTrial()">
                    Commencer l'essai gratuit
                </button>
            </div>
        </div>

        <!-- Plan Mensuel -->
        <div class="col-md-4">
            <div class="pricing-card">
                <h3>Plan Mensuel</h3>
                <div class="display-4 my-4">4.99$</div>
                <p class="text-muted">par mois</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Toutes les fonctionnalités du plan gratuit</li>
                    <li><i class="fas fa-check"></i> Support prioritaire 24/7</li>
                    <li><i class="fas fa-check"></i> Sauvegarde automatique</li>
                    <li><i class="fas fa-check"></i> Mises à jour régulières</li>
                </ul>
                <button class="btn btn-primary w-100 mt-4" onclick="selectPlan('monthly')">
                    Choisir ce plan
                </button>

            </div>
        </div>

        <!-- Plan Annuel -->
        <div class="col-md-4">
            <div class="pricing-card">
                <h3>Plan Annuel</h3>
                <div class="display-4 my-4">49$</div>
                <p class="text-muted">par an</p>
                <div class="text-success mb-3">Économisez 17%</div>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Toutes les fonctionnalités du plan mensuel</li>
                    <li><i class="fas fa-check"></i> Formation personnalisée</li>
                    <li><i class="fas fa-check"></i> API personnalisée</li>
                    <li><i class="fas fa-check"></i> Support dédié</li>
                </ul>
                <button class="btn btn-primary w-100 mt-4" onclick="selectPlan('yearly')">
                    Choisir ce plan
                </button>
            </div>
        </div>
    </div>
</div>
