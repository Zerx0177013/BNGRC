/**
 * Region Form - JavaScript
 * Gestion du formulaire de région
 */
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('regionForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var formData = new FormData(form);
    var isEdit = typeof IS_EDIT !== 'undefined' && IS_EDIT;
    var url = form.action;

    fetch(url, {
      method: isEdit ? 'PUT' : 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(formData).toString()
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success) {
        window.location.href = BASE_URL + '/regions';
      } else {
        alert(data.message || 'Erreur lors de l\'enregistrement.');
      }
    })
    .catch(function () { alert('Erreur réseau.'); });
  });
});
