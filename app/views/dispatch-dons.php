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

      <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
  <script src="<?= BASE_URL ?>/public/assets/js/dispatch-dons.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
