/**
 * Dispatch Dons - JavaScript
 * Gestion de la sélection et du dispatch des dons
 */
document.addEventListener('DOMContentLoaded', function () {
  var checkAll = document.getElementById('checkAll');
  var checkboxes = document.querySelectorAll('.don-checkbox');
  var btnDispatch = document.getElementById('btnDispatch');
  var selectedCount = document.getElementById('selectedCount');

  function updateUI() {
    var checkedCount = document.querySelectorAll('.don-checkbox:checked').length;
    btnDispatch.disabled = checkedCount === 0;
    
    if (checkedCount > 0) {
      selectedCount.textContent = '(' + checkedCount + ' sélectionné' + (checkedCount > 1 ? 's' : '') + ')';
    } else {
      selectedCount.textContent = '';
    }

    var allChecked = Array.from(checkboxes).every(function(cb) { return cb.checked; });
    checkAll.checked = allChecked && checkboxes.length > 0;
  }

  if (checkAll) {
    checkAll.addEventListener('change', function () {
      checkboxes.forEach(function (cb) {
        cb.checked = checkAll.checked;
      });
      updateUI();
    });
  }

  checkboxes.forEach(function (cb) {
    cb.addEventListener('change', updateUI);
  });

  if (btnDispatch) {
    btnDispatch.addEventListener('click', function () {
      var selected = Array.from(document.querySelectorAll('.don-checkbox:checked'))
        .map(function (cb) { return cb.value; });

      if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un don.');
        return;
      }

      if (!confirm('Voulez-vous dispatcher ' + selected.length + ' don(s) ?')) {
        return;
      }

      btnDispatch.disabled = true;
      btnDispatch.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Dispatch en cours...';

      var formData = new URLSearchParams();
      selected.forEach(function (id) {
        formData.append('dons[]', id);
      });

      fetch(BASE_URL + '/dispatch/execute', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
      })
      .then(function (res) { 
        console.log('Response status:', res.status);
        return res.json(); 
      })
      .then(function (data) {
        console.log('Response data:', data);
        if (data.success) {
          showAlert('success', data.message + ' (' + data.count + ' attribution(s) effectuée(s))');
          setTimeout(function () {
            window.location.reload();
          }, 1500);
        } else {
          showAlert('danger', data.message || 'Erreur lors du dispatch.');
          btnDispatch.disabled = false;
          btnDispatch.innerHTML = '<i class="bi bi-truck me-1"></i> Dispatcher les dons sélectionnés';
        }
      })
      .catch(function (err) {
        console.error('Fetch error:', err);
        showAlert('danger', 'Erreur réseau: ' + err.message);
        btnDispatch.disabled = false;
        btnDispatch.innerHTML = '<i class="bi bi-truck me-1"></i> Dispatcher les dons sélectionnés';
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
