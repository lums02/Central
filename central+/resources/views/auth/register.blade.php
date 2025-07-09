{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - Système Hospitalier</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    
    @vite('resources/css/register.css')
</head>
<body>
<div class="register-container">
    <h1 class="mb-4 text-center">Inscription Hôpital</h1>

    {{-- Affichage des erreurs de validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="logo" class="form-label">Logo de l'hôpital (optionnel)</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*" />
        </div>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l'hôpital</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required />
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}" placeholder="+243 812345678" required />
        </div>

        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" id="adresse" rows="2" class="form-control" required>{{ old('adresse') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="type_hopital" class="form-label">Type d'hôpital</label>
            <select name="type_hopital" id="type_hopital" class="form-control" required>
                <option value="" disabled {{ old('type_hopital') ? '' : 'selected' }}>Sélectionnez un type</option>
                <option value="Général" {{ old('type_hopital') == 'Général' ? 'selected' : '' }}>Général</option>
                <option value="Spécialisé" {{ old('type_hopital') == 'Spécialisé' ? 'selected' : '' }}>Spécialisé</option>
                <option value="Clinique" {{ old('type_hopital') == 'Clinique' ? 'selected' : '' }}>Clinique</option>
                <option value="Centre Médical" {{ old('type_hopital') == 'Centre Médical' ? 'selected' : '' }}>Centre Médical</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe administrateur</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required />
        </div>

        <button type="submit" class="btn-register">S'inscrire</button>
    </form>

    <p class="text-center mt-3">
        Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
    </p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
