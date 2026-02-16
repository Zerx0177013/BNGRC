(function () {
  'use strict';

  var deleteModal = null;
  var currentDeleteId = null;
  var filtreVille = null;
  var tableContainer = null;
  var totalCount = null;
  var alertContainer = null;

  document.addEventListener('DOMContentLoaded', function () {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    filtreVille = document.getElementById('filtreVille');
    tableContainer = document.getElementById('achatsTableContainer');
    totalCount = document.getElementById('totalCount');
    alertContainer = document.getElementById('alertContainer');

    if (!filtreVille || !tableContainer || !totalCount) {
      console.error('Erreur: éléments DOM manquants');
      return;
    }

    var initialDataElement = document.getElementById('initialAchatsData');
    if (initialDataElement) {
      try {
        var initialData = JSON.parse(initialDataElement.textContent);
        renderTable(initialData);
      } catch (e) {
        console.error('Erreur parsing JSON:', e);
        renderTable([]);
      }
    } else {
      renderTable([]);
    }

    filtreVille.addEventListener('change', handleFilterChange);

    document.getElementById('btnConfirmDelete').addEventListener('click', handleDeleteConfirm);
  });

  function handleFilterChange() {
    var idVille = filtreVille.value;
    var url = window.BASE_URL + '/achats/json';
    
    if (idVille) {
      url += '?id_ville=' + encodeURIComponent(idVille);
    }

    console.log('Filtre changé - ID Ville:', idVille);
    console.log('URL:', url);

    fetch(url)
      .then(function (response) {
        console.log('Status:', response.status);
        if (!response.ok) {
          throw new Error('HTTP ' + response.status);
        }
        return response.json();
      })
      .then(function (data) {
        console.log('Données:', data);
        if (data.success && Array.isArray(data.achats)) {
          renderTable(data.achats);
        } else {
          throw new Error('Format de réponse invalide');
        }
      })
      .catch(function (error) {
        console.error('Erreur:', error);
        showAlert('danger', 'Erreur lors du chargement des données: ' + error.message);
      });
  }

  function renderTable(achats) {
    if (!tableContainer || !totalCount) {
      console.error('Conteneur non trouvé');
      return;
    }

    totalCount.textContent = achats.length;

    if (achats.length === 0) {
      tableContainer.innerHTML = 
        '<div class="text-center text-muted py-5">' +
        '<i class="bi bi-inbox fs-1 d-block mb-3"></i>' +
        '<p class="fs-5">Aucun achat pour cette sélection</p>' +
        '</div>';
      return;
    }

    var html = '<div class="table-responsive">' +
      '<table class="table table-bordered table-hover">' +
      '<thead class="table-dark">' +
      '<tr>' +
      '<th>#</th>' +
      '<th>Date</th>' +
      '<th>Ville</th>' +
      '<th>Article</th>' +
      '<th>Catégorie</th>' +
      '<th>Quantité</th>' +
      '<th>Prix unitaire</th>' +
      '<th>Frais</th>' +
      '<th>Montant total</th>' +
      '<th>Don</th>' +
      '<th>Action</th>' +
      '</tr>' +
      '</thead>' +
      '<tbody>';

    achats.forEach(function (achat) {
      var date = new Date(achat.date_achat);
      var dateStr = formatDate(date);

      html += '<tr>' +
        '<td>' + escapeHtml(achat.id_achat) + '</td>' +
        '<td>' + dateStr + '</td>' +
        '<td>' + escapeHtml(achat.nom_ville) + '</td>' +
        '<td>' + escapeHtml(achat.nom_article) + '</td>' +
        '<td><span class="badge bg-secondary">' + escapeHtml(achat.nom_categorie) + '</span></td>' +
        '<td>' + formatNumber(achat.quantite) + '</td>' +
        '<td>' + formatNumber(achat.prix_unitaire) + ' Ar</td>' +
        '<td>' + Math.round(parseFloat(achat.frais_percent)) + '%</td>' +
        '<td class="fw-bold">' + formatNumber(achat.montant_total) + ' Ar</td>' +
        '<td><small class="text-muted">Don #' + escapeHtml(achat.id_don) + '</small></td>' +
        '<td>' +
        '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' + escapeHtml(achat.id_achat) + '">' +
        '<i class="bi bi-trash"></i>' +
        '</button>' +
        '</td>' +
        '</tr>';
    });

    html += '</tbody></table></div>';

    tableContainer.innerHTML = html;

    attachDeleteListeners();
  }

  function attachDeleteListeners() {
    var deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        currentDeleteId = btn.getAttribute('data-id');
        if (deleteModal && currentDeleteId) {
          deleteModal.show();
        }
      });
    });
  }

  function handleDeleteConfirm() {
    if (!currentDeleteId) {
      console.error('Aucun ID à supprimer');
      return;
    }

    var url = window.BASE_URL + '/achats/' + encodeURIComponent(currentDeleteId);

    fetch(url, { method: 'DELETE' })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('HTTP ' + response.status);
        }
        return response.json();
      })
      .then(function (data) {
        if (data.success) {
          showAlert('success', data.message || 'Achat supprimé avec succès');
          deleteModal.hide();
          currentDeleteId = null;
          
          setTimeout(function () {
            handleFilterChange();
          }, 1000);
        } else {
          throw new Error(data.message || 'Erreur lors de la suppression');
        }
      })
      .catch(function (error) {
        console.error('Erreur:', error);
        showAlert('danger', 'Erreur: ' + error.message);
      });
  }

  function showAlert(type, message) {
    if (!alertContainer) return;

    alertContainer.innerHTML = 
      '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
      escapeHtml(message) +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>' +
      '</div>';

    setTimeout(function () {
      alertContainer.innerHTML = '';
    }, 5000);
  }

  function formatDate(date) {
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    var hours = ('0' + date.getHours()).slice(-2);
    var minutes = ('0' + date.getMinutes()).slice(-2);
    return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes;
  }

  function formatNumber(num) {
    return parseFloat(num).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
  }

  function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

})();
