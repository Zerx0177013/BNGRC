<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Dispatch des dons</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dispatch</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">

            <!-- Alert message -->
            <div id="alertContainer"></div>
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                  <i class="bi bi-gift-fill me-2"></i>Dons disponibles
                </h3>
                <div>
                  <a href="<?= BASE_URL ?>/dispatch/history" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-clock-history me-1"></i> Historique
                  </a>
                  <button type="button" class="btn btn-info btn-sm me-2" id="btnSimulate" disabled>
                    <i class="bi bi-eye me-1"></i> Simuler
                  </button>
                  <button type="button" class="btn btn-primary btn-sm" id="btnDispatch" disabled>
                    <i class="bi bi-truck me-1"></i> Dispatcher les dons sélectionnés
                  </button>
                </div>
              </div>
              <div class="card-body">
                
                <?php if (empty($dons)): ?>
                  <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Aucun don disponible pour le dispatch.
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th style="width: 50px">
                            <input type="checkbox" id="checkAll" class="form-check-input">
                          </th>
                          <th style="width: 60px">#</th>
                          <th>Article</th>
                          <th>Catégorie</th>
                          <th>Quantité totale</th>
                          <th>Quantité restante</th>
                          <th>Date du don</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($dons as $index => $don): ?>
                          <tr>
                            <td class="text-center">
                              <input 
                                type="checkbox" 
                                class="form-check-input don-checkbox" 
                                value="<?= $don['id_don'] ?>"
                                data-quantite="<?= $don['quantite_restante'] ?>"
                              >
                            </td>
                            <td><?= $don['id_don'] ?></td>
                            <td><?= htmlspecialchars($don['nom_article']) ?></td>
                            <td>
                              <span class="badge bg-secondary">
                                <?= htmlspecialchars($don['nom_categorie']) ?>
                              </span>
                            </td>
                            <td><?= number_format($don['quantite'], 2, ',', ' ') ?></td>
                            <td>
                              <span class="badge bg-success">
                                <?= number_format($don['quantite_restante'], 2, ',', ' ') ?>
                              </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($don['date_don'])) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>

              </div>
              <div class="card-footer text-muted">
                Total : <strong><?= count($dons) ?></strong> don(s) disponible(s)
                <span id="selectedCount" class="ms-3 text-primary fw-bold"></span>
              </div>
            </div>

            <!-- Section de simulation (cachée par défaut) -->
            <div id="simulationSection" style="display: none;">
              <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Mode Simulation :</strong> Les données affichées sont une simulation. Aucune donnée n'est enregistrée dans la base de données.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
              </div>

              <div class="row">
                <!-- Tableau des dispatches simulés -->
                <div class="col-12 mb-4">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title"><i class="bi bi-truck me-2"></i>Dispatches Simulés par Ville et Article</h3>
                    </div>
                    <div class="card-body p-0">
                      <table class="table table-sm table-bordered mb-0" id="simulationDispatchTable" style="table-layout: fixed; width: 100%;">
                        <thead class="table-light">
                          <tr>
                            <th style="width:90px;font-size:1.05rem;font-weight:600;padding:6px 4px;">Ville</th>
                            <th class="text-center" style="font-size:0.95rem;padding:4px;">
                              <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                              </div>
                            </th>
                          </tr>
                        </thead>
                        <tbody style="font-size:1rem;">
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

                <!-- Graphique comparatif -->
                <div class="col-12">
                  <div class="card mb-4">
                    <div class="card-header border-0">
                      <h3 class="card-title"><i class="bi bi-bar-chart-fill me-2"></i>Comparaison : Dons / Dispatches Réels / Dispatches Simulés</h3>
                    </div>
                    <div class="card-body">
                      <div class="position-relative" style="height: 480px;">
                        <div id="simulation-comparison-chart"></div>
                      </div>
                      <div class="d-flex flex-row justify-content-center mt-3">
                        <span class="me-3">
                          <i class="bi bi-square-fill text-danger"></i> Dons
                        </span>
                        <span class="me-3">
                          <i class="bi bi-square-fill text-success"></i> Dispatches Réels
                        </span>
                        <span>
                          <i class="bi bi-square-fill text-primary"></i> Dispatches Simulés
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          var checkAll = document.getElementById('checkAll');
          var checkboxes = document.querySelectorAll('.don-checkbox');
          var btnDispatch = document.getElementById('btnDispatch');
          var btnSimulate = document.getElementById('btnSimulate');
          var selectedCount = document.getElementById('selectedCount');

          function updateUI() {
            var checkedCount = document.querySelectorAll('.don-checkbox:checked').length;
            btnDispatch.disabled = checkedCount === 0;
            btnSimulate.disabled = checkedCount === 0;
            
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

          // Bouton Simuler
          if (btnSimulate) {
            btnSimulate.addEventListener('click', function () {
              var selected = Array.from(document.querySelectorAll('.don-checkbox:checked'))
                .map(function (cb) { return cb.value; });

              if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un don.');
                return;
              }

              // Charger les données de simulation via AJAX
              btnSimulate.disabled = true;
              btnSimulate.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Simulation...';

              var formData = new URLSearchParams();
              selected.forEach(function (id) {
                formData.append('dons[]', id);
              });

              fetch('<?= BASE_URL ?>/dispatch/simulate-data', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
              })
              .then(function (res) { return res.json(); })
              .then(function (data) {
                console.log('Simulation data:', data);
                if (data.success) {
                  // Afficher la section de simulation
                  document.getElementById('simulationSection').style.display = 'block';
                  
                  // Remplir le tableau
                  renderSimulationTable(data.dispatchesData);
                  
                  // Remplir le graphique
                  renderComparisonChart(data.dispatchParCategorie, data.dispatchSimuleParCategorie, data.donsParCategorie);
                } else {
                  showAlert('danger', data.message || 'Erreur lors de la simulation.');
                }
                btnSimulate.disabled = false;
                btnSimulate.innerHTML = '<i class="bi bi-eye me-1"></i> Simuler';
              })
              .catch(function (err) {
                console.error('Fetch error:', err);
                showAlert('danger', 'Erreur réseau: ' + err.message);
                btnSimulate.disabled = false;
                btnSimulate.innerHTML = '<i class="bi bi-eye me-1"></i> Simuler';
              });
            });
          }

          // Bouton Dispatcher
          if (btnDispatch) {
            btnDispatch.addEventListener('click', function () {
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

              fetch('<?= BASE_URL ?>/dispatch/execute', {
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

          function escapeHtml(text) {
            var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
          }

          function renderSimulationTable(dispatchesData) {
            var catColors = {
              'Nature': { bg: 'info', text: 'text-info', textDark: '#0a58ca' },
              'Matériaux': { bg: 'warning', text: 'text-warning', textDark: '#cc7a00' },
              'Argent': { bg: 'success', text: 'text-success', textDark: '#146c43' }
            };

            if (!dispatchesData || !dispatchesData.articles || dispatchesData.articles.length === 0) {
              document.querySelector('#simulationDispatchTable tbody').innerHTML = 
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
            document.querySelector('#simulationDispatchTable thead').innerHTML = headerHtml;

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
                  '<i class="bi bi-geo-alt-fill text-primary"></i>' + escapeHtml(currentRegion) +
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

            document.querySelector('#simulationDispatchTable tbody').innerHTML = bodyHtml;
          }

          function renderComparisonChart(dispatchReels, dispatchSimules, dons) {
            if (!dons || dons.length === 0) {
              document.querySelector('#simulation-comparison-chart').innerHTML = 
                '<div class="text-center text-muted py-5">Aucune donnée pour le graphique</div>';
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
              chart: { type: 'bar', height: 450, toolbar: { show: false } },
              plotOptions: {
                bar: { horizontal: false, columnWidth: '70%', dataLabels: { position: 'top' } }
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

            var chart = new ApexCharts(document.querySelector('#simulation-comparison-chart'), options);
            chart.render();
          }
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
