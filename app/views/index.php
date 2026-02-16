<?php
include __DIR__ . '/header.php';

// Préparer les couleurs des catégories
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
                <h3 class="mb-0">Dashboard</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        
        <div class="app-content">
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              
              <!-- ========== COLONNE GAUCHE ========== -->
              <div class="col-lg-6">
                
                <!-- VILLES - BESOINS -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-house-exclamation me-2"></i>Besoins par Ville</h3>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                          <tr>
                            <th style="width:80px;font-size:0.75rem;">Ville</th>
                            <?php foreach ($besoinsData['articles'] as $art): 
                              $catStyle = $catColors[$art['nom_categorie']] ?? ['bg' => 'secondary', 'text' => 'text-secondary'];
                            ?>
                            <th class="text-center bg-<?= $catStyle['bg'] ?> bg-opacity-10" style="font-size:0.7rem;padding:4px;">
                              <?= htmlspecialchars(substr($art['nom_article'], 0, 10)) ?>
                            </th>
                            <?php endforeach; ?>
                          </tr>
                        </thead>
                        <tbody style="font-size:0.8rem;">
                          <?php
                          $currentRegion = null;
                          foreach ($besoinsData['villes'] as $ville):
                            // Séparateur de région
                            if ($currentRegion !== $ville['nom_region']):
                              $currentRegion = $ville['nom_region'];
                          ?>
                          <tr class="table-active">
                            <td colspan="<?= count($besoinsData['articles']) + 1 ?>" class="fw-bold" style="font-size:0.75rem;">
                              <i class="bi bi-geo-alt-fill text-primary"></i><?= htmlspecialchars($currentRegion) ?>
                            </td>
                          </tr>
                          <?php endif; ?>
                          
                          <tr>
                            <td class="ps-2"><?= htmlspecialchars($ville['nom_ville']) ?></td>
                            <?php foreach ($besoinsData['articles'] as $art): 
                              $qte = $besoinsData['matrix'][$ville['id_ville']][$art['id_article']] ?? 0;
                              $catStyle = $catColors[$art['nom_categorie']] ?? ['bg' => 'secondary', 'text' => 'text-secondary'];
                            ?>
                            <td class="text-center <?= $catStyle['text'] ?> fw-semibold">
                              <?= $qte > 0 ? number_format($qte, 0, ',', ' ') : '-' ?>
                            </td>
                            <?php endforeach; ?>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- TABLEAU DES DONS -->
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title"><i class="bi bi-gift-fill me-2"></i>Dons par Article</h3>
                    <div class="card-tools">
                      <a href="javascript:void(0)" class="btn btn-tool btn-sm">
                        <i class="bi bi-download"></i>
                      </a>
                    </div>
                  </div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                      <thead>
                        <tr>
                          <th>Article</th>
                          <th>Qté totale</th>
                          <th>Catégorie</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($donsParArticle as $don): ?>
                          <tr>
                            <td><?= htmlspecialchars($don['nom_article']) ?></td>
                            <td><?= number_format($don['total_quantite'], 0, ',', ' ') ?></td>
                            <td><?php
                              $cat = strtolower($don['nom_categorie']);
                              $badge = match(true) {
                                str_contains($cat, 'argent') => 'text-bg-success',
                                str_contains($cat, 'mat')    => 'text-bg-warning',
                                default                      => 'text-bg-info',
                              };
                            ?><span class="badge <?= $badge ?>"><?= htmlspecialchars($don['nom_categorie']) ?></span></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
              <!-- /.col-lg-6 -->

              <!-- ========== COLONNE DROITE ========== -->
              <div class="col-lg-6">
                
                <!-- VILLES - DISPATCHES -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-truck me-2"></i>Dispatches par Ville</h3>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                          <tr>
                            <th style="width:80px;font-size:0.75rem;">Ville</th>
                            <?php foreach ($dispatchesData['articles'] as $art): 
                              $catStyle = $catColors[$art['nom_categorie']] ?? ['bg' => 'secondary', 'text' => 'text-secondary'];
                            ?>
                            <th class="text-center bg-<?= $catStyle['bg'] ?> bg-opacity-10" style="font-size:0.7rem;padding:4px;">
                              <?= htmlspecialchars(substr($art['nom_article'], 0, 10)) ?>
                            </th>
                            <?php endforeach; ?>
                          </tr>
                        </thead>
                        <tbody style="font-size:0.8rem;">
                          <?php
                          $currentRegion = null;
                          foreach ($dispatchesData['villes'] as $ville):
                            // Séparateur de région
                            if ($currentRegion !== $ville['nom_region']):
                              $currentRegion = $ville['nom_region'];
                          ?>
                          <tr class="table-active">
                            <td colspan="<?= count($dispatchesData['articles']) + 1 ?>" class="fw-bold" style="font-size:0.75rem;">
                              <i class="bi bi-geo-alt-fill text-primary"></i><?= htmlspecialchars($currentRegion) ?>
                            </td>
                          </tr>
                          <?php endif; ?>
                          
                          <tr>
                            <td class="ps-2"><?= htmlspecialchars($ville['nom_ville']) ?></td>
                            <?php foreach ($dispatchesData['articles'] as $art): 
                              $qte = $dispatchesData['matrix'][$ville['id_ville']][$art['id_article']] ?? 0;
                              $catStyle = $catColors[$art['nom_categorie']] ?? ['bg' => 'secondary', 'text' => 'text-secondary'];
                            ?>
                            <td class="text-center <?= $catStyle['text'] ?> fw-semibold">
                              <?= $qte > 0 ? number_format($qte, 0, ',', ' ') : '-' ?>
                            </td>
                            <?php endforeach; ?>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- GRAPHIQUE DISPATCHES VS DONATIONS -->
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title"><i class="bi bi-bar-chart-fill me-2"></i>Dispatches vs Donations</h3>
                  </div>
                  <div class="card-body">
                    <div class="position-relative" style="height: 250px;">
                      <div id="comparison-chart"></div>
                    </div>
                    <div class="d-flex flex-row justify-content-center mt-3">
                      <span class="me-3">
                        <i class="bi bi-square-fill text-primary"></i> Dispatches
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
<?php include __DIR__ . '/footer.php'; ?>

    <script>
      // --- Données PHP injectées ---
      const dispatchParCategorie = <?= json_encode($dispatchParCategorie) ?>;
      const donsParCategorie = <?= json_encode($donsParCategorie) ?>;

      // --- Préparation des données pour le graphique groupé ---
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

      // --- Graphique à barres groupées ---
      const comparison_chart_options = {
        series: [
          {
            name: 'Dispatches',
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
        document.querySelector('#comparison-chart'),
        comparison_chart_options,
      );
      comparison_chart.render();
    </script>
    <script src="<?= BASE_URL ?>/public/assets/js/dashboard.js"></script>
