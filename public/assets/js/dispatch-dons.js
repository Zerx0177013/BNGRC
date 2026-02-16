(function () {
  'use strict';

  var checkAll = null;
  var checkboxes = null;
  var btnDispatch = null;
  var btnSimulate = null;
  var selectedCount = null;
  var alertContainer = null;

  document.addEventListener('DOMContentLoaded', function () {
    checkAll = document.getElementById('checkAll');
    checkboxes = document.querySelectorAll('.don-checkbox');
    btnDispatch = document.getElementById('btnDispatch');
    btnSimulate = document.getElementById('btnSimulate');
    selectedCount = document.getElementById('selectedCount');
    alertContainer = document.getElementById('alertContainer');

    initializeEventListeners();
  });

  function initializeEventListeners() {
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

    if (btnSimulate) {
      btnSimulate.addEventListener('click', handleSimulate);
    }

    if (btnDispatch) {
      btnDispatch.addEventListener('click', handleDispatch);
    }
  }

  function updateUI() {
    var checkedCount = document.querySelectorAll('.don-checkbox:checked').length;
    
    if (btnDispatch) btnDispatch.disabled = checkedCount === 0;
    if (btnSimulate) btnSimulate.disabled = checkedCount === 0;
    
    if (selectedCount) {
      if (checkedCount > 0) {
        selectedCount.textContent = '(' + checkedCount + ' sélectionné' + (checkedCount > 1 ? 's' : '') + ')';
      } else {
        selectedCount.textContent = '';
      }
    }

    if (checkAll && checkboxes.length > 0) {
      var allChecked = Array.from(checkboxes).every(function(cb) { return cb.checked; });
      checkAll.checked = allChecked;
    }
  }

  function handleSimulate() {
    var selected = Array.from(document.querySelectorAll('.don-checkbox:checked'))
      .map(function (cb) { return cb.value; });

    if (selected.length === 0) {
      alert('Veuillez sélectionner au moins un don.');
      return;
    }

    btnSimulate.disabled = true;
    btnSimulate.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Simulation...';

    var formData = new URLSearchParams();
    selected.forEach(function (id) {
      formData.append('dons[]', id);
    });

    fetch(window.BASE_URL + '/dispatch/simulate-data', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    })
    .then(function (res) { 
      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }
      return res.json(); 
    })
    .then(function (data) {
      console.log('Simulation data:', data);
      if (data.success) {
        var simulationSection = document.getElementById('simulationSection');
        if (simulationSection) {
          simulationSection.style.display = 'block';
        }
        
        renderSimulationTable(data.dispatchesData);
        
        renderComparisonChart(data.dispatchParCategorie, data.dispatchSimuleParCategorie, data.donsParCategorie);
        
        simulationSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        showAlert('danger', data.message || 'Erreur lors de la simulation.');
      }
    })
    .catch(function (err) {
      console.error('Fetch error:', err);
      showAlert('danger', 'Erreur réseau: ' + err.message);
    })
    .finally(function () {
      btnSimulate.disabled = false;
      btnSimulate.innerHTML = '<i class="bi bi-eye me-1"></i> Simuler';
    });
  }

  function handleDispatch() {
    var selected = Array.from(document.querySelectorAll('.don-checkbox:checked'))
      .map(function (cb) { return cb.value; });

    if (selected.length === 0) {
      alert('Veuillez sélectionner au moins un don.');
      return;
    }

    if (!confirm('Voulez-vous dispatcher ' + selected.length + ' don(s) ?\n\nAttention : Cette action enregistrera directement dans la base de données.\nUtilisez le bouton "Simuler" pour prévisualiser avant d\'enregistrer.')) {
      return;
    }

    btnDispatch.disabled = true;
    btnDispatch.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Dispatch en cours...';

    var formData = new URLSearchParams();
    selected.forEach(function (id) {
      formData.append('dons[]', id);
    });

    fetch(window.BASE_URL + '/dispatch/execute', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    })
    .then(function (res) { 
      console.log('Response status:', res.status);
      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }
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
        throw new Error(data.message || 'Erreur lors du dispatch.');
      }
    })
    .catch(function (err) {
      console.error('Fetch error:', err);
      showAlert('danger', 'Erreur: ' + err.message);
      btnDispatch.disabled = false;
      btnDispatch.innerHTML = '<i class="bi bi-truck me-1"></i> Dispatcher les dons sélectionnés';
    });
  }

  function showAlert(type, message) {
    if (!alertContainer) return;

    alertContainer.innerHTML = 
      '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
      escapeHtml(message) +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>' +
      '</div>';

    document.querySelector('.app-content').scrollIntoView({ behavior: 'smooth', block: 'start' });

    setTimeout(function () {
      var alert = alertContainer.querySelector('.alert');
      if (alert) {
        alert.classList.remove('show');
        setTimeout(function () {
          alertContainer.innerHTML = '';
        }, 150);
      }
    }, 5000);
  }

  // Échapper le HTML
  function escapeHtml(text) {
    var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
  }

  // Rendre le tableau de simulation
  function renderSimulationTable(dispatchesData) {
    var catColors = {
      'Nature': { bg: 'info', text: 'text-info', textDark: '#0a58ca' },
      'Matériaux': { bg: 'warning', text: 'text-warning', textDark: '#cc7a00' },
      'Argent': { bg: 'success', text: 'text-success', textDark: '#146c43' }
    };

    var tableHead = document.querySelector('#simulationDispatchTable thead');
    var tableBody = document.querySelector('#simulationDispatchTable tbody');

    if (!tableHead || !tableBody) {
      console.error('Éléments du tableau introuvables');
      return;
    }

    if (!dispatchesData || !dispatchesData.articles || dispatchesData.articles.length === 0) {
      tableBody.innerHTML = 
        '<tr><td colspan="100%" class="text-center text-muted py-3">Aucune donnée à afficher</td></tr>';
      return;
    }

    // En-têtes
    var headerHtml = '<tr><th style="width:90px;font-size:1.05rem;font-weight:600;padding:6px 4px;">Ville</th>';
    dispatchesData.articles.forEach(function(art) {
      var catStyle = catColors[art.nom_categorie] || { bg: 'secondary', text: 'text-secondary', textDark: '#6c757d' };
      headerHtml += '<th class="text-center bg-' + catStyle.bg + ' bg-opacity-10" ' +
        'style="font-size:0.95rem;padding:6px 2px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" ' +
        'title="' + escapeHtml(art.nom_article) + '">' +
        escapeHtml(art.nom_article.substring(0, 8)) + '</th>';
    });
    headerHtml += '</tr>';
    tableHead.innerHTML = headerHtml;

    // Corps du tableau - uniquement les villes avec simulation
    var bodyHtml = '';
    var currentRegion = null;

    dispatchesData.villes.forEach(function(ville) {
      // Vérifier si cette ville a des dispatches simulés
      var hasData = false;
      dispatchesData.articles.forEach(function(art) {
        var qte = dispatchesData.matrix[ville.id_ville] ? dispatchesData.matrix[ville.id_ville][art.id_article] || 0 : 0;
        if (qte > 0) hasData = true;
      });

      // Ne montrer que les villes avec des dispatches simulés
      if (!hasData) return;

      // Séparateur de région
      if (currentRegion !== ville.nom_region) {
        currentRegion = ville.nom_region;
        bodyHtml += '<tr class="table-active">' +
          '<td colspan="' + (dispatchesData.articles.length + 1) + '" class="fw-bold" style="font-size:1rem;padding:6px 4px;">' +
          '<i class="bi bi-geo-alt-fill text-primary"></i> ' + escapeHtml(currentRegion) +
          '</td></tr>';
      }

      bodyHtml += '<tr><td class="ps-2 fw-semibold" style="font-size:1rem;padding:6px 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="' + escapeHtml(ville.nom_ville) + '">' + escapeHtml(ville.nom_ville) + '</td>';
      dispatchesData.articles.forEach(function(art) {
        var qte = dispatchesData.matrix[ville.id_ville] ? dispatchesData.matrix[ville.id_ville][art.id_article] || 0 : 0;
        var catStyle = catColors[art.nom_categorie] || { bg: 'secondary', text: 'text-secondary', textDark: '#6c757d' };
        var displayValue = qte > 0 ? Number(qte).toLocaleString('fr-FR') : '-';
        bodyHtml += '<td class="text-center fw-bold" style="font-size:1rem;padding:6px 2px;color:' + catStyle.textDark + ';">' + displayValue + '</td>';
      });
      bodyHtml += '</tr>';
    });

    if (bodyHtml === '') {
      bodyHtml = '<tr><td colspan="100%" class="text-center text-muted py-3">Aucune ville concernée par la simulation</td></tr>';
    }

    tableBody.innerHTML = bodyHtml;
  }

  // Rendre le graphique de comparaison
  function renderComparisonChart(dispatchReels, dispatchSimules, dons) {
    var chartContainer = document.querySelector('#simulation-comparison-chart');
    
    if (!chartContainer) {
      console.error('Conteneur du graphique introuvable');
      return;
    }

    if (!dons || dons.length === 0) {
      chartContainer.innerHTML = 
        '<div class="text-center text-muted py-5">Aucune donnée pour le graphique</div>';
      return;
    }

    // Vérifier si ApexCharts est disponible
    if (typeof ApexCharts === 'undefined') {
      console.error('ApexCharts n\'est pas chargé');
      chartContainer.innerHTML = 
        '<div class="text-center text-danger py-5">Erreur: ApexCharts n\'est pas chargé</div>';
      return;
    }

    var categories = dons.map(function(d) { return d.nom_categorie; });
    
    // Données Dons
    var donsData = dons.map(function(d) {
      var val = parseFloat(d.total);
      return d.nom_categorie === 'Argent' ? val / 1000 : val;
    });

    // Données Dispatches Réels
    var dispatchReelsData = categories.map(function(cat) {
      var found = dispatchReels.find(function(d) { return d.nom_categorie === cat; });
      var val = found ? parseFloat(found.total) : 0;
      return cat === 'Argent' ? val / 1000 : val;
    });

    // Données Dispatches Simulés
    var dispatchSimulesData = categories.map(function(cat) {
      var found = dispatchSimules.find(function(d) { return d.nom_categorie === cat; });
      var val = found ? parseFloat(found.total) : 0;
      return cat === 'Argent' ? val / 1000 : val;
    });

    var options = {
      series: [
        { name: 'Dons', data: donsData, color: '#dc3545' },
        { name: 'Dispatches Réels', data: dispatchReelsData, color: '#198754' },
        { name: 'Dispatches Simulés', data: dispatchSimulesData, color: '#0d6efd' }
      ],
      chart: { 
        type: 'bar', 
        height: 450, 
        toolbar: { show: false },
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 800
        }
      },
      plotOptions: {
        bar: { 
          horizontal: false, 
          columnWidth: '70%', 
          dataLabels: { position: 'top' } 
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) { return val > 0 ? Math.round(val) : ''; },
        offsetY: -20,
        style: { fontSize: '16px', colors: ['#304758'], fontWeight: 'bold' }
      },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      xaxis: { categories: categories },
      yaxis: {
        title: { text: 'Quantité (Argent en milliers)' },
        forceNiceScale: true
      },
      fill: { opacity: 1 },
      tooltip: {
        y: {
          formatter: function (val, opts) {
            var cat = opts.w.globals.labels[opts.dataPointIndex];
            if (cat === 'Argent') {
              return (val * 1000).toLocaleString('fr-FR') + ' Ar';
            }
            return val.toLocaleString('fr-FR');
          }
        }
      },
      legend: { position: 'bottom', horizontalAlign: 'center' }
    };

    // Détruire le graphique existant si présent
    chartContainer.innerHTML = '';

    var chart = new ApexCharts(chartContainer, options);
    chart.render();
  }

})();
