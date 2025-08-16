<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-md"></i>
            <h1>Connexion à la plateforme</h1>
            <p>Choisissez votre entité pour accéder à l’espace de gestion</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background:#f8d7da; color:#842029; padding:10px; border-radius:5px;">
                <ul class="mb-0" style="margin:0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="type_utilisateur"><i class="fas fa-user-tag"></i> Type d'entité</label>
                <select class="form-control" id="type_utilisateur" name="type_utilisateur" required>
                    <option value="">-- Sélectionnez --</option>
                    <option value="hopital">Hôpital</option>
                    <option value="pharmacie">Pharmacie</option>
                    <option value="banque_sang">Banque de sang</option>
                    <option value="centre">Centre médical</option>
                    <option value="patient">Patient</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus />
            </div>

            <div class="form-group">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required />
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="register-link">
            <p>Pas encore inscrit ? <a href="{{ route('register.form') }}">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>
