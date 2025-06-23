<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur CENTRAL - Système de Gestion Hospitalière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/css/home.css'])
</head>
<body>
    <!-- Section Hero -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 mb-4">Bienvenue sur <strong> CENTRAL+</strong></h1>
            <p class="lead">La solution complète de gestion hospitalière</p>
            <div class="mt-5">
                <a href="#pricing" class="btn btn-light btn-lg">Découvrir nos offres</a>
            </div>
        </div>
    </div>

    <!-- Section Présentation CENTRAL+ -->
    <div class="container py-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="display-5 mb-4">Transformez votre Gestion Hospitalière avec <strong>CENTRAL+</strong></h2>
                <p class="lead text-muted">Une solution innovante qui révolutionne la gestion des établissements de santé en RDC.</p>
                <div class="mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-hospital-user fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Gestion Complète des Patients</h5>
                            <p class="text-muted mb-0">Suivez l'historique médical, les rendez-vous et les traitements de chaque patient.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-calendar-check fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Planification Intelligente</h5>
                            <p class="text-muted mb-0">Optimisez les emplois du temps des médecins et réduisez les temps d'attente.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-file-medical fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-1">Dossiers Médicaux Numériques</h5>
                            <p class="text-muted mb-0">Accédez instantanément aux dossiers médicaux sécurisés et partageables.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                         alt="Hôpital moderne" 
                         class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 bg-primary text-white p-3 rounded-3 m-3">
                        <h3 class="mb-0">+500</h3>
                        <p class="mb-0">Hôpitaux Satisfaits</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Pourquoi Choisir CENTRAL+ -->
        <div class="row mt-5">
            <div class="col-12 text-center mb-5">
                <h2 class="display-6">Pourquoi Choisir CENTRAL+ ?</h2>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h4>Sécurité Maximale</h4>
                        <p class="text-muted">Protection des données sensibles conforme aux normes internationales.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h4>Performance Optimale</h4>
                        <p class="text-muted">Interface rapide et intuitive pour une productivité accrue.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h4>Support 24/7</h4>
                        <p class="text-muted">Une équipe dédiée à votre service pour une assistance continue.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Tarifs -->
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

    @include('partials.payment-modal')
    @include('partials.orange-password-modal')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/app.js'])
</body>
</html>
