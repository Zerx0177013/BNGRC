document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('articleForm');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                prix: formData.get('prix'),
                id_categorie: formData.get('id_categorie')
            };

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher un message de succès
                    alert('Article ajouté avec succès!');
                    // Rediriger vers la liste des articles
                    window.location.href = BASE_URL + '/articles';
                } else {
                    alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de l\'ajout de l\'article');
            });
        });
    }
});
