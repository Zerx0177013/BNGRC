/**
 * Villes - JavaScript
 * Gestion de la liste des villes avec suppression
 */
document.addEventListener('DOMContentLoaded', function () {
  let deleteId = null;
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

  // Delete button click
  document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
      deleteId = this.dataset.id;
      document.getElementById('deleteVilleName').textContent = this.dataset.name;
      deleteModal.show();
    });
  });

  // Confirm delete
  document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (!deleteId) return;

    fetch(BASE_URL + '/villes/' + deleteId, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      deleteModal.hide();
      if (data.success) {
        var row = document.getElementById('ville-row-' + deleteId);
        if (row) row.remove();
        showAlert('success', 'Ville supprimée avec succès.');
      } else {
        showAlert('danger', data.message || 'Erreur lors de la suppression.');
      }
    })
    .catch(function () {
      deleteModal.hide();
      showAlert('danger', 'Erreur réseau.');
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
