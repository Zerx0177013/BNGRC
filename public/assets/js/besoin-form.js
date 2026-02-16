/**
 * Besoin Form - JavaScript
 * Gestion du formulaire de besoin avec calcul du montant
 */
document.addEventListener('DOMContentLoaded', function () {
  const articleSelect = document.getElementById('id_article');
  const quantiteInput = document.getElementById('quantite');
  const prixDisplay = document.getElementById('prix_unitaire_display');
  const montantDisplay = document.getElementById('montant_total_display');

  function updateMontant() {
    const selected = articleSelect.options[articleSelect.selectedIndex];
    const prix = selected ? parseFloat(selected.dataset.prix) || 0 : 0;
    const qte = parseFloat(quantiteInput.value) || 0;

    prixDisplay.value = prix > 0 ? prix.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' Ar' : '';
    montantDisplay.value = (prix * qte) > 0 ? (prix * qte).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' Ar' : '';
  }

  articleSelect.addEventListener('change', updateMontant);
  quantiteInput.addEventListener('input', updateMontant);

  // Form submission via fetch
  document.getElementById('besoinForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const isEdit = typeof IS_EDIT !== 'undefined' && IS_EDIT;
    const url = form.action;

    fetch(url, {
      method: isEdit ? 'PUT' : 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(formData).toString()
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        window.location.href = BASE_URL + '/besoins';
      } else {
        alert(data.message || 'Erreur lors de l\'enregistrement.');
      }
    })
    .catch(() => alert('Erreur réseau.'));
  });
});
