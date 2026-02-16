/**
 * Recap - JavaScript
 * Gestion de la page de récapitulation avec actualisation Ajax
 */
document.addEventListener('DOMContentLoaded', function () {
  var btnActualiser = document.getElementById('btnActualiser');
  var refreshIcon = document.getElementById('refreshIcon');
  var refreshSpinner = document.getElementById('refreshSpinner');

  btnActualiser.addEventListener('click', function () {
    // Désactiver le bouton et afficher le spinner
    btnActualiser.disabled = true;
    refreshIcon.classList.add('d-none');
    refreshSpinner.classList.remove('d-none');

    fetch(BASE_URL + '/recap/data')
      .then(function (res) {
        if (!res.ok) {
          throw new Error('Erreur HTTP ' + res.status);
        }
        return res.json();
      })
      .then(function (response) {
        console.log('Données reçues:', response);
        
        if (response.success && response.data) {
          var data = response.data;
          
          // Mettre à jour les 3 cards principales
          document.getElementById('montantTotal').textContent = 
            formatNumber(data.montant_total) + ' Ar';
          
          document.getElementById('montantSatisfait').textContent = 
            formatNumber(data.montant_satisfait) + ' Ar';
          
          document.getElementById('montantRestant').textContent = 
            formatNumber(data.montant_restant) + ' Ar';
          
          document.getElementById('pourcentageSatisfait').textContent = 
            formatNumber(data.pourcentage_satisfait) + '%';

          // Animation de mise à jour réussie
          btnActualiser.classList.add('btn-success');
          setTimeout(function () {
            btnActualiser.classList.remove('btn-success');
            btnActualiser.classList.add('btn-primary');
          }, 1000);
        } else {
          throw new Error(response.message || 'Erreur lors de la récupération des données');
        }
      })
      .catch(function (err) {
        console.error('Erreur:', err);
        alert('Erreur lors de l\'actualisation: ' + err.message);
      })
      .finally(function () {
        // Réactiver le bouton et masquer le spinner
        btnActualiser.disabled = false;
        refreshIcon.classList.remove('d-none');
        refreshSpinner.classList.add('d-none');
      });
  });

  function formatNumber(num) {
    return parseFloat(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
  }
});
