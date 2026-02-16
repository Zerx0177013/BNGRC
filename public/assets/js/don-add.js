/**
 * Don Add - JavaScript
 * Gestion du formulaire d'ajout de don
 */
document.addEventListener('DOMContentLoaded', function () {
  const articleSelect = document.getElementById('id_article');
  const besoinSelect = document.getElementById('id_besoin');

  function filterBesoins() {
    const selectedArticleId = articleSelect.value;
    const besoinOptions = besoinSelect.querySelectorAll('option');
    const villesDetail = document.getElementById('villesDetail');
    const villesText = document.getElementById('villesText');

    // Afficher les villes qui ont besoin de cet article
    const selectedOption = articleSelect.options[articleSelect.selectedIndex];
    if (selectedArticleId && selectedOption.dataset.villes) {
      villesText.textContent = 'Villes en attente : ' + selectedOption.dataset.villes;
      villesDetail.style.display = '';
    } else {
      villesDetail.style.display = 'none';
    }

    besoinOptions.forEach((option, index) => {
      if (index === 0) return; // Skip "Sélectionner un besoin"

      const besoinArticleId = option.dataset.articleId;
      
      if (!selectedArticleId || besoinArticleId === selectedArticleId) {
        option.style.display = '';
      } else {
        option.style.display = 'none';
      }
    });

    const selectedBesoin = besoinSelect.options[besoinSelect.selectedIndex];
    if (selectedBesoin && selectedBesoin.dataset.articleId !== selectedArticleId && selectedArticleId) {
      besoinSelect.value = '';
    }
  }

  const quantiteInput = document.getElementById('quantite');
  const quantiteWarning = document.getElementById('quantiteWarning');
  const quantiteWarningText = document.getElementById('quantiteWarningText');

  function checkQuantite() {
    const selectedOption = articleSelect.options[articleSelect.selectedIndex];
    const besoinRestant = selectedOption ? parseFloat(selectedOption.dataset.besoinRestant) : 0;
    const quantite = parseFloat(quantiteInput.value) || 0;

    if (articleSelect.value && quantite > 0 && besoinRestant > 0 && quantite > besoinRestant) {
      var surplus = (quantite - besoinRestant).toFixed(2);
      quantiteWarningText.textContent = 'La quantité dépasse le besoin restant (' + besoinRestant.toFixed(2).replace('.', ',') + '). Le surplus de ' + surplus.replace('.', ',') + ' ne pourra pas être dispatché.';
      quantiteWarning.style.display = '';
    } else {
      quantiteWarning.style.display = 'none';
    }
  }

  quantiteInput.addEventListener('input', checkQuantite);

  articleSelect.addEventListener('change', function () {
    filterBesoins();
    checkQuantite();
  });

  filterBesoins();

  // Form submission via fetch
  document.getElementById('donForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const url = form.action;

    fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(formData).toString()
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Don enregistré avec succès !');
        form.reset();
        filterBesoins(); // Reset le filtre après reset du formulaire
      } else {
        alert(data.message || 'Erreur lors de l\'enregistrement du don.');
      }
    })
    .catch(() => alert('Erreur réseau.'));
  });
});
