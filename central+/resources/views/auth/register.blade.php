{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - Plateforme Santé</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    @vite('resources/css/register.css')
</head>
<body>
<div class="register-container mx-auto p-4 mt-5 bg-white rounded shadow" style="max-width: 500px;">
    <h1 class="mb-4 text-center text-primary">Inscription</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="mb-3">
            <label for="type_utilisateur" class="form-label"><i class="fas fa-building me-2"></i>Type d'entité</label>
            <select name="type_utilisateur" id="type_utilisateur" class="form-select" required onchange="toggleFields()">
                <option value="" disabled selected>-- Sélectionnez une entité --</option>
                <option value="hopital" {{ old('type_utilisateur') == 'hopital' ? 'selected' : '' }}>Hôpital</option>
                <option value="pharmacie" {{ old('type_utilisateur') == 'pharmacie' ? 'selected' : '' }}>Pharmacie</option>
                <option value="banque_sang" {{ old('type_utilisateur') == 'banque_sang' ? 'selected' : '' }}>Banque de sang</option>
                <option value="centre" {{ old('type_utilisateur') == 'centre' ? 'selected' : '' }}>Centre médical</option>
                <option value="patient" {{ old('type_utilisateur') == 'patient' ? 'selected' : '' }}>Patient</option>
            </select>
        </div>

        {{-- Champs communs à toutes les entités --}}
        <div class="mb-3">
            <label for="nom" class="form-label"><i class="fas fa-user me-2"></i>Nom complet ou raison sociale</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required />
        </div>



        <div class="mb-3" id="adresse-group">
            <label for="adresse" class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Adresse</label>
            <textarea name="adresse" id="adresse" rows="2" class="form-control">{{ old('adresse') }}</textarea>
        </div>

        {{-- Champs spécifiques hôpital, pharmacie, banque_sang, centre --}}
        <div class="mb-3" id="logo-group">
            <label for="logo" class="form-label"><i class="fas fa-file-image me-2"></i>Logo (optionnel)</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*" />
        </div>

        <div class="mb-3" id="type_hopital-group">
            <label for="type_hopital" class="form-label"><i class="fas fa-clinic-medical me-2"></i>Type d'hôpital</label>
            <select name="type_hopital" id="type_hopital" class="form-select">
                <option value="" disabled selected>Sélectionnez un type</option>
                <option value="Général" {{ old('type_hopital') == 'Général' ? 'selected' : '' }}>Général</option>
                <option value="Spécialisé" {{ old('type_hopital') == 'Spécialisé' ? 'selected' : '' }}>Spécialisé</option>
                <option value="Clinique" {{ old('type_hopital') == 'Clinique' ? 'selected' : '' }}>Clinique</option>
                <option value="Centre Médical" {{ old('type_hopital') == 'Centre Médical' ? 'selected' : '' }}>Centre Médical</option>
            </select>
        </div>

        {{-- Champs spécifiques patients --}}
        <div class="mb-3" id="date_naissance-group" style="display:none;">
            <label for="date_naissance" class="form-label"><i class="fas fa-birthday-cake me-2"></i>Date de naissance</label>
            <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance') }}" />
        </div>

        <div class="mb-3" id="sexe-group" style="display:none;">
            <label for="sexe" class="form-label"><i class="fas fa-venus-mars me-2"></i>Sexe</label>
            <select name="sexe" id="sexe" class="form-select">
                <option value="" disabled selected>Sélectionnez</option>
                <option value="masculin" {{ old('sexe') == 'masculin' ? 'selected' : '' }}>Masculin</option>
                <option value="feminin" {{ old('sexe') == 'feminin' ? 'selected' : '' }}>Féminin</option>
            </select>
        </div>

        {{-- Champs communs mot de passe --}}
        <div class="mb-3">
            <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label"><i class="fas fa-lock me-2"></i>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required />
        </div>

        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
    </form>

    <p class="text-center mt-3">
        Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
    </p>
</div>

<script>
    function toggleFields() {
        const type = document.getElementById('type_utilisateur').value;
        // Champs spécifiques
        const logoGroup = document.getElementById('logo-group');
        const typeHopitalGroup = document.getElementById('type_hopital-group');
        const dateNaissanceGroup = document.getElementById('date_naissance-group');
        const sexeGroup = document.getElementById('sexe-group');
        const adresseGroup = document.getElementById('adresse-group');

        // Cacher tout par défaut
        logoGroup.style.display = 'none';
        typeHopitalGroup.style.display = 'none';
        dateNaissanceGroup.style.display = 'none';
        sexeGroup.style.display = 'none';
        adresseGroup.style.display = 'none';

        // Montrer selon entité
        if (type === 'hopital' || type === 'pharmacie' || type === 'banque_sang' || type === 'centre') {
            logoGroup.style.display = 'block';
            adresseGroup.style.display = 'block';
            if(type === 'hopital'){
                typeHopitalGroup.style.display = 'block';
            }
        } else if(type === 'patient') {
            dateNaissanceGroup.style.display = 'block';
            sexeGroup.style.display = 'block';
        }
    }

    // Appeler au chargement pour gérer la sélection précédente
    document.addEventListener('DOMContentLoaded', toggleFields);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
