<?php include __DIR__ . '/header.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
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
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Donations reçues</h3>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <p class="d-flex flex-column">
                        <span class="fw-bold fs-5"><?= number_format($totalDons['nb']) ?></span>
                        <span>Donations au fil du temps</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                      <div id="visitors-chart"></div>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                      <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Cette semaine
                      </span>

                      <span> <i class="bi bi-square-fill text-secondary"></i> Semaine dernière </span>
                    </div>
                  </div>
                </div>
                <!-- /.card -->

                <div class="card mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title">Dons</h3>
                    <div class="card-tools">
                      <a href="javascript:void(0)" class="btn btn-tool btn-sm">
                        <i class="bi bi-download"></i>
                      </a>
                      <a href="javascript:void(0)" class="btn btn-tool btn-sm">
                        <i class="bi bi-list"></i>
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
                <!-- /.card -->
              </div>
              <!-- /.col-md-6 -->
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Dispatches</h3>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <p class="d-flex flex-column">
                        <span class="fw-bold fs-5"><?= number_format($totalDispatches['nb']) ?></span>
                        <span>Dispatches effectués</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                      <div id="sales-chart"></div>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                      <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Cette année
                      </span>

                      <span> <i class="bi bi-square-fill text-secondary"></i> Année dernière </span>
                    </div>
                  </div>
                </div>
                <!-- /.card -->

                <div class="card">
                  <div class="card-header border-0">
                    <h3 class="card-title">Aperçu des opérations</h3>
                  </div>
                  <div class="card-body">
                    <div
                      class="d-flex justify-content-between align-items-center border-bottom mb-3"
                    >
                      <p class="text-success fs-2">
                        <i class="bi bi-gift-fill"></i>
                      </p>
                      <p class="d-flex flex-column text-end">
                        <span class="fw-bold">
                          <i class="bi bi-graph-up-arrow text-success"></i> <?= $tauxDons ?>%
                        </span>
                        <span class="text-secondary">TAUX DE DONS</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
                    <div
                      class="d-flex justify-content-between align-items-center border-bottom mb-3"
                    >
                      <p class="text-info fs-2">
                        <i class="bi bi-truck"></i>
                      </p>
                      <p class="d-flex flex-column text-end">
                        <span class="fw-bold">
                          <i class="bi bi-graph-up-arrow text-info"></i> <?= $tauxDispatch ?>%
                        </span>
                        <span class="text-secondary">TAUX DE DISPATCH</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                      <p class="text-warning fs-2">
                        <i class="bi bi-people-fill"></i>
                      </p>
                      <p class="d-flex flex-column text-end">
                        <span class="fw-bold">
                          <i class="bi bi-graph-up-arrow text-warning"></i>
                          5%
                        </span>
                        <span class="text-secondary">NOUVEAUX DONATEURS</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
                  </div>
                </div>
              </div>
              <!-- /.col-md-6 -->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
<?php include __DIR__ . '/footer.php'; ?>

    <script>
      // --- Données PHP injectées ---
      const donsParJour = <?= json_encode($donsParJour) ?>;
      const dispatchParCategorie = <?= json_encode($dispatchParCategorie) ?>;
    </script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
