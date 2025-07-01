
        function handleLogin(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };

            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'role.php';
                } else {
                    alert(data.message || 'Une erreur est survenue lors de la connexion');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la connexion');
            });

            return false;
        }