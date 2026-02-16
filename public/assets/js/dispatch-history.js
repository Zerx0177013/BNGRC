/**
 * Dispatch History - JavaScript
 * Gestion de l'historique des dispatches et réinitialisation
 */
document.addEventListener('DOMContentLoaded', function () {
  var btnClearAll = document.getElementById('btnClearAll');
  var clearModal = null;

  if (btnClearAll) {
    clearModal = new bootstrap.Modal(document.getElementById('clearModal'));

    btnClearAll.addEventListener('click', function () {
      clearModal.show();
    });

    document.getElementById('confirmClearBtn').addEventListener('click', function () {
      fetch(BASE_URL + '/dispatch/clear', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        clearModal.hide();
        if (data.success) {
          showAlert('success', data.message);
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        } else {
          showAlert('danger', data.message || 'Erreur lors de la suppression.');
        }
      })
      .catch(function () {
        clearModal.hide();
        showAlert('danger', 'Erreur réseau.');
      });
    });
  }

  function showAlert(type, message) {
    var container = document.getElementById('alertContainer');
    container.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>' +
      '</div>';
  }
});
