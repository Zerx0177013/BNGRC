/**
 * Achats Besoins - JavaScript
 * Gestion du formulaire d'achat via dons en argent
 */
document.addEventListener('DOMContentLoaded', function () {
  var achatModal = new bootstrap.Modal(document.getElementById('achatModal'));
  var achatForm = document.getElementById('achatForm');
  var btnAchats = document.querySelectorAll('.btn-achat');

  var idBesoinInput = document.getElementById('id_besoin');
  var fraisPercentInput = document.getElementById('frais_percent');
  var besoinInfo = document.getElementById('besoinInfo');
  var idDonSelect = document.getElementById('id_don');
  var quantiteInput = document.getElementById('quantite');
  var quantiteMax = document.getElementById('quantiteMax');
  var prixUnitaireSpan = document.getElementById('prixUnitaire');
  var montantHTSpan = document.getElementById('montantHT');
  var montantFraisSpan = document.getElementById('montantFrais');
  var montantTotalSpan = document.getElementById('montantTotal');
  var fraisLabel = document.getElementById('fraisLabel');
  var warningInsuffisant = document.getElementById('warningInsuffisant');
  var btnConfirm = document.getElementById('btnConfirmAchat');

  var prixUnitaire = 0;
  var quantiteMaxVal = 0;
  var fraisPercent = 0;

  btnAchats.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var idBesoin = btn.dataset.idBesoin;
      var article = btn.dataset.article;
      var ville = btn.dataset.ville;
      var prix = parseFloat(btn.dataset.prix);
      var qteMax = parseFloat(btn.dataset.quantiteMax);
      var frais = parseFloat(btn.dataset.frais);

      idBesoinInput.value = idBesoin;
      fraisPercentInput.value = frais;
      besoinInfo.textContent = ville + ' - ' + article;
      quantiteMax.textContent = qteMax.toFixed(2).replace('.', ',');
      quantiteInput.max = qteMax;
      quantiteInput.value = '';
      idDonSelect.value = '';
      fraisLabel.textContent = frais.toFixed(0);

      prixUnitaire = prix;
      quantiteMaxVal = qteMax;
      fraisPercent = frais;

      updateCalcul();
      achatModal.show();
    });
  });

  quantiteInput.addEventListener('input', updateCalcul);
  idDonSelect.addEventListener('change', updateCalcul);

  function updateCalcul() {
    var qte = parseFloat(quantiteInput.value) || 0;
    var montantHT = qte * prixUnitaire;
    var frais = montantHT * (fraisPercent / 100);
    var total = montantHT + frais;

    prixUnitaireSpan.textContent = prixUnitaire.toFixed(2).replace('.', ',') + ' Ar';
    montantHTSpan.textContent = montantHT.toFixed(2).replace('.', ',') + ' Ar';
    montantFraisSpan.textContent = frais.toFixed(2).replace('.', ',') + ' Ar';
    montantTotalSpan.textContent = total.toFixed(2).replace('.', ',') + ' Ar';

    var selectedOption = idDonSelect.options[idDonSelect.selectedIndex];
    if (selectedOption && selectedOption.dataset.montant) {
      var montantDispo = parseFloat(selectedOption.dataset.montant);
      if (total > montantDispo) {
        warningInsuffisant.style.display = '';
        btnConfirm.disabled = true;
      } else {
        warningInsuffisant.style.display = 'none';
        btnConfirm.disabled = false;
      }
    } else {
      warningInsuffisant.style.display = 'none';
      btnConfirm.disabled = false;
    }
  }

  achatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(achatForm);

    btnConfirm.disabled = true;
    btnConfirm.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> En cours...';

    fetch(BASE_URL + '/achats', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(formData).toString()
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success) {
        showAlert('success', data.message);
        achatModal.hide();
        setTimeout(function () { window.location.reload(); }, 1500);
      } else {
        showAlert('danger', data.message);
        btnConfirm.disabled = false;
        btnConfirm.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmer l\'achat';
      }
    })
    .catch(function (err) {
      showAlert('danger', 'Erreur réseau: ' + err.message);
      btnConfirm.disabled = false;
      btnConfirm.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmer l\'achat';
    });
  });

  function showAlert(type, message) {
    var container = document.getElementById('alertContainer');
    container.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>' +
      '</div>';
  }
});
