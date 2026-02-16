/**
 * Achats Liste - JavaScript
 * Gestion de la liste des achats avec filtrage et suppression
 */
document.addEventListener('DOMContentLoaded', function () {
  var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
  var btnConfirmDelete = document.getElementById('btnConfirmDelete');
  var currentDeleteId = null;
  var filtreVille = document.getElementById('filtreVille');

  filtreVille.addEventListener('change', function () {
    var idVille = filtreVille.value;
    var url = BASE_URL + '/achats/json' + (idVille ? '?id_ville=' + idVille : '');
    
    console.log('ID Ville sélectionné:', idVille);
    console.log('URL appelée:', url);

    fetch(url)
      .then(function (res) { 
        console.log('Response status:', res.status);
        return res.json(); 
      })
      .then(function (data) {
        console.log('Données reçues:', data);
        if (data.success) {
          updateTable(data.achats);
        } else {
          console.error('Erreur API:', data);
        }
      })
      .catch(function (err) {
        console.error('Erreur fetch:', err);
      });
  });

  function updateTable(achats) {
    var tableContainer = document.getElementById('achatsTable');
    var totalCount = document.getElementById('totalCount');

    if (achats.length === 0) {
      tableContainer.parentElement.innerHTML = 
        '<div class="text-center text-muted py-4">' +
        '<i class="bi bi-inbox fs-3 d-block mb-2"></i>' +
        'Aucun achat pour cette ville.' +
        '</div>';
      totalCount.textContent = '0';
      return;
    }

    var tbody = '<tbody>';
    achats.forEach(function (achat) {
      var date = new Date(achat.date_achat);
      var dateStr = ('0' + date.getDate()).slice(-2) + '/' + 
                    ('0' + (date.getMonth() + 1)).slice(-2) + '/' + 
                    date.getFullYear() + ' ' +
                    ('0' + date.getHours()).slice(-2) + ':' +
                    ('0' + date.getMinutes()).slice(-2);

      tbody += '<tr>' +
        '<td>' + achat.id_achat + '</td>' +
        '<td>' + dateStr + '</td>' +
        '<td>' + achat.nom_ville + '</td>' +
        '<td>' + achat.nom_article + '</td>' +
        '<td><span class="badge bg-secondary">' + achat.nom_categorie + '</span></td>' +
        '<td>' + parseFloat(achat.quantite).toFixed(2).replace('.', ',') + '</td>' +
        '<td>' + parseFloat(achat.prix_unitaire).toFixed(2).replace('.', ',') + ' Ar</td>' +
        '<td>' + parseFloat(achat.frais_percent).toFixed(0) + '%</td>' +
        '<td class="fw-bold">' + parseFloat(achat.montant_total).toFixed(2).replace('.', ',') + ' Ar</td>' +
        '<td><small class="text-muted">Don #' + achat.id_don + '</small></td>' +
        '<td>' +
        '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' + achat.id_achat + '">' +
        '<i class="bi bi-trash"></i>' +
        '</button>' +
        '</td>' +
        '</tr>';
    });
    tbody += '</tbody>';

    tableContainer.innerHTML = 
      '<table class="table table-bordered table-hover">' +
      '<thead class="table-dark">' +
      '<tr>' +
      '<th>#</th><th>Date</th><th>Ville</th><th>Article</th><th>Catégorie</th>' +
      '<th>Quantité</th><th>Prix unitaire</th><th>Frais</th><th>Montant total</th>' +
      '<th>Don</th><th>Action</th>' +
      '</tr>' +
      '</thead>' +
      tbody +
      '</table>';

    totalCount.textContent = achats.length;
    attachDeleteListeners();
  }

  function attachDeleteListeners() {
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
      btn.addEventListener('click', function () {
        currentDeleteId = btn.dataset.id;
        deleteModal.show();
      });
    });
  }

  attachDeleteListeners();

  btnConfirmDelete.addEventListener('click', function () {
    if (!currentDeleteId) return;

    fetch(BASE_URL + '/achats/' + currentDeleteId, {
      method: 'DELETE'
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success) {
        showAlert('success', data.message);
        deleteModal.hide();
        setTimeout(function () { window.location.reload(); }, 1500);
      } else {
        showAlert('danger', data.message);
      }
    })
    .catch(function (err) {
      showAlert('danger', 'Erreur réseau: ' + err.message);
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
