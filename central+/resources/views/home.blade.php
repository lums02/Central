<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CENTRAL+ - Système de Gestion Hospitalière</title>

    <!-- CSS externes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Ton CSS via Vite -->
    @vite('resources/css/home.css')
    @vite(['resources/js/home.js'])

</head>
<body>

    <!-- SECTION HERO -->
    <div class="hero-section text-center py-5 text-white">
        <div class="container">
            <h1 class="display-4 mb-4">Bienvenue sur <strong>CENTRAL+</strong></h1>
            <p class="lead">La solution complète de gestion hospitalière</p>
            <a href="#pricing" class="btn btn-light btn-lg mt-4">Découvrir nos offres</a>
        </div>
    </div>

    <!-- SECTION PRÉSENTATION, POURQUOI CHOISIR, TARIFS, MODALS, etc. -->
    @include('partials.presentation')
    @include('partials.pricing')
    @include('partials.payment_modal')



    <!-- JS externes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Ton JS via Vite -->
    @vite('resources/js/home.js')

</body>
</html>
