
        let logoData = null;

        function handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoData = e.target.result; // Sauvegarder l'image en base64
                    const uploadDiv = event.target.parentElement;
                    uploadDiv.style.backgroundImage = `url(${logoData})`;
                    uploadDiv.style.backgroundSize = 'cover';
                    uploadDiv.style.backgroundPosition = 'center';
                    uploadDiv.querySelector('i').style.display = 'none';
                    uploadDiv.querySelector('.upload-text').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }

        function handleRegister(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = {
                nom: formData.get('nom'),
                email: formData.get('email'),
                password: formData.get('password'),
                confirm_password: formData.get('confirm_password'),
                telephone: formData.get('telephone'),
                adresse: formData.get('adresse'),
                type_hopital: formData.get('type_hopital'),
                logo: logoData // Ajouter le logo aux données
            };

            // Validation côté client
            if (data.password !== data.confirm_password) {
                alert('Les mots de passe ne correspondent pas');
                return false;
            }

            if (data.password.length < 8) {
                alert('Le mot de passe doit contenir au moins 8 caractères');
                return false;
            }

            // Envoi des données au serveur
            fetch('register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Inscription réussie ! Vous allez être redirigé vers la page de connexion.');
                    window.location.href = 'role.php';
                } else {
                    alert(data.message || 'Une erreur est survenue lors de l\'inscription');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'inscription');
            });

            return false;
        }
