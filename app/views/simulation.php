<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>

<?php
$catColors = [
    'Nature' => ['bg' => 'info', 'text' => 'text-info'],
    'Matériaux' => ['bg' => 'warning', 'text' => 'text-warning'],
    'Argent' => ['bg' => 'success', 'text' => 'text-success'],
];
?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Simulation de Dispatch</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dispatch">Dispatch</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Simulation</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        
        <div class="app-content">
          <div class="container-fluid">
            
            <!-- Alert Info -->
            <div class="alert alert-info alert-dismissible fade show" role="alert">
              <i class="bi bi-info-circle me-2"></i>
              <strong>Mode Simulation :</strong> Les données affichées sont une simulation. Aucune modification n'a été effectuée dans la base de données.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>

            <!-- Actions -->
            <div class="row mb-3">
              <div class="col-12 d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/dispatch" class="btn btn-secondary">
                  <i class="bi bi-arrow-left me-1"></i> Retour au Dispatch
                </a>
                <button type="button" class="btn btn-success" id="btnConfirmDispatch">
                  <i class="bi bi-check-circle me-1"></i> Confirmer et Enregistrer
                </button>
              </div>
            </div>

            <!--begin::Row-->
            <div class="row">
              
              <!-- ========== COLONNE GAUCHE: VILLES - DISPATCHES SIMULÉS ========== -->
              <div class="col-lg-6">
                
                <!-- VILLES - DISPATCHES -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-truck me-2"></i>Dispatches Simulés par Ville</h3>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered mb-0" id="simulationDispatchTable">
                        <thead class="table-light">
                          <tr>
                            <th style="width:80px;font-size:0.75rem;">Ville</th>
                            <th class="text-center" style="font-size:0.7rem;padding:4px;">
                              <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                              </div>
                            </th>
                          </tr>
                        </thead>
                        <tbody style="font-size:0.8rem;">
                          <tr>
                            <td colspan="100%" class="text-center text-muted py-3">
                              Chargement des données de simulation...
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>
              <!-- /.col-lg-6 -->

              <!-- ========== COLONNE DROITE: GRAPHIQUE ========== -->
              <div class="col-lg-6">
                
                <!-- GRAPHIQUE DISPATCHES VS DONATIONS -->
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title"><i class="bi bi-bar-chart-fill me-2"></i>Dispatches vs Donations (Simulé)</h3>
                  </div>
                  <div class="card-body">
                    <div class="position-relative" style="height: 250px;">
                      <div id="simulation-comparison-chart"></div>
                    </div>
                    <div class="d-flex flex-row justify-content-center mt-3">
                      <span class="me-3">
                        <i class="bi bi-square-fill text-primary"></i> Dispatches (Simulés)
                      </span>
                      <span>
                        <i class="bi bi-square-fill text-danger"></i> Donations
                      </span>
                    </div>
                  </div>
                </div>

              </div>
              <!-- /.col-lg-6 -->

            </div>
            <!--end::Row-->
          </div>
        </div>
      </main>
      <!--end::App Main-->

<script>
document.addEventListener('DOMContentLoaded', function () {
  const urlParams = new URLSearchParams(window.location.search);
  const donsParam = urlParams.get('dons');
  
  if (!donsParam) {
    alert('Aucun don sélectionné pour la simulation');
    window.location.href = '<?= BASE_URL ?>/dispatch';
    return;
  }

  const donIds = donsParam.split(',');

  loadSimulationData(donIds);

  document.getElementById('btnConfirmDispatch')?.addEventListener('click', function () {
    if (!confirm('Voulez-vous confirmer et enregistrer ces dispatches dans la base de données ?')) {
      return;
    }

    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...';

    const formData = new URLSearchParams();
    donIds.forEach(id => formData.append('dons[]', id));

    fetch('<?= BASE_URL ?>/dispatch/execute', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Dispatches enregistrés avec succès !');
        window.location.href = '<?= BASE_URL ?>/dispatch/history';
      } else {
        alert('Erreur : ' + (data.message || 'Erreur inconnue'));
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-check-circle me-1"></i> Confirmer et Enregistrer';
      }
    })
    .catch(err => {
      alert('Erreur réseau : ' + err.message);
      this.disabled = false;
      this.innerHTML = '<i class="bi bi-check-circle me-1"></i> Confirmer et Enregistrer';
    });
  });
});

function loadSimulationData(donIds) {
  const formData = new URLSearchParams();
  donIds.forEach(id => formData.append('dons[]', id));

  fetch('<?= BASE_URL ?>/dispatch/simulate-data', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: formData.toString()
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      renderDispatchTable(data.dispatchesData);
      renderComparisonChart(data.dispatchParCategorie, data.donsParCategorie);
    } else {
      alert('Erreur : ' + (data.message || 'Erreur lors de la simulation'));
      window.location.href = '<?= BASE_URL ?>/dispatch';
    }
  })
  .catch(err => {
    console.error('Erreur lors du chargement des données:', err);
    alert('Erreur réseau : ' + err.message);
  });
}

function renderDispatchTable(dispatchesData) {
  const catColors = {
    'Nature': { bg: 'info', text: 'text-info' },
    'Matériaux': { bg: 'warning', text: 'text-warning' },
    'Argent': { bg: 'success', text: 'text-success' }
  };

  if (!dispatchesData || !dispatchesData.articles || dispatchesData.articles.length === 0) {
    document.querySelector('#simulationDispatchTable tbody').innerHTML = 
      '<tr><td colspan="100%" class="text-center text-muted py-3">Aucune donnée à afficher</td></tr>';
    return;
  }

  let headerHtml = '<tr><th style="width:80px;font-size:0.75rem;">Ville</th>';
  dispatchesData.articles.forEach(art => {
    const catStyle = catColors[art.nom_categorie] || { bg: 'secondary', text: 'text-secondary' };
    headerHtml += `<th class="text-center bg-${catStyle.bg} bg-opacity-10" style="font-size:0.7rem;padding:4px;">
      ${escapeHtml(art.nom_article.substring(0, 10))}
    </th>`;
  });
  headerHtml += '</tr>';
  document.querySelector('#simulationDispatchTable thead').innerHTML = headerHtml;

  let bodyHtml = '';
  let currentRegion = null;

  dispatchesData.villes.forEach(ville => {
    if (currentRegion !== ville.nom_region) {
      currentRegion = ville.nom_region;
      bodyHtml += `<tr class="table-active">
        <td colspan="${dispatchesData.articles.length + 1}" class="fw-bold" style="font-size:0.75rem;">
          <i class="bi bi-geo-alt-fill text-primary"></i>${escapeHtml(currentRegion)}
        </td>
      </tr>`;
    }

    bodyHtml += `<tr><td class="ps-2">${escapeHtml(ville.nom_ville)}</td>`;
    dispatchesData.articles.forEach(art => {
      const qte = dispatchesData.matrix[ville.id_ville]?.[art.id_article] || 0;
      const catStyle = catColors[art.nom_categorie] || { bg: 'secondary', text: 'text-secondary' };
      const displayValue = qte > 0 ? Number(qte).toLocaleString('fr-FR') : '-';
      bodyHtml += `<td class="text-center ${catStyle.text} fw-semibold">${displayValue}</td>`;
    });
    bodyHtml += '</tr>';
  });

  document.querySelector('#simulationDispatchTable tbody').innerHTML = bodyHtml;
}

function renderComparisonChart(dispatchParCategorie, donsParCategorie) {
  if (!dispatchParCategorie || dispatchParCategorie.length === 0) {
    document.querySelector('#simulation-comparison-chart').innerHTML = 
      '<div class="text-center text-muted py-5">Aucune donnée pour le graphique</div>';
    return;
  }

  const categories = dispatchParCategorie.map(d => d.nom_categorie);
  
  const dispatchData = dispatchParCategorie.map(d => {
    const val = parseFloat(d.total);
    return d.nom_categorie === 'Argent' ? val / 1000 : val;
  });

  const donsData = categories.map(catName => {
    const don = donsParCategorie.find(d => d.nom_categorie === catName);
    if (!don) return 0;
    const val = parseFloat(don.total);
    return catName === 'Argent' ? val / 1000 : val;
  });

  const categoriesLabels = categories.map(cat => 
    cat === 'Argent' ? cat + ' (×1 000)' : cat
  );

  const comparison_chart_options = {
    series: [
      {
        name: 'Dispatches (Simulés)',
        data: dispatchData,
      },
      {
        name: 'Donations',
        data: donsData,
      }
    ],
    chart: {
      type: 'bar',
      height: 230,
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '70%',
        endingShape: 'rounded',
      },
    },
    colors: ['#0d6efd', '#dc3545'],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent'],
    },
    xaxis: {
      categories: categoriesLabels,
    },
    fill: {
      opacity: 1,
    },
    legend: {
      show: false,
    },
    tooltip: {
      y: {
        formatter: function (val, { seriesIndex, dataPointIndex }) {
          const cat = categories[dataPointIndex];
          if (cat === 'Argent') {
            return (val * 1000).toLocaleString('fr-FR') + ' Ar';
          }
          return val.toLocaleString('fr-FR') + ' unités';
        },
      },
    },
  };

  const comparison_chart = new ApexCharts(
    document.querySelector('#simulation-comparison-chart'),
    comparison_chart_options,
  );
  comparison_chart.render();
}

function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}
</script>

<?php include __DIR__ . '/footer.php'; ?>
