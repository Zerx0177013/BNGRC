<?php require_once(__DIR__ . '/header.php'); ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><?= count($villes) ?> Villes — Besoins & Dispatch</h3>
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
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <?php foreach ($villes as $ville): ?>
                <div class="col-md-6">
                  <div class="card mb-4">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        <?= htmlspecialchars($ville['nom_ville']) ?>
                        <small class="text-secondary ms-2">— <?= htmlspecialchars($ville['nom_region']) ?></small>
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <?php if (!empty($ville['besoins'])): ?>
                        <table class="table table-bordered table-striped align-middle">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Article</th>
                              <th>Catégorie</th>
                              <th>Quantité</th>
                              <th>Montant total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($ville['besoins'] as $besoin): ?>
                              <tr>
                                <td><?= $besoin['id_besoin'] ?></td>
                                <td><?= htmlspecialchars($besoin['nom_article']) ?></td>
                                <td><?php
                                  $cat = strtolower($besoin['nom_categorie']);
                                  $badgeClass = match(true) {
                                    str_contains($cat, 'argent') => 'text-bg-success',
                                    str_contains($cat, 'mat')    => 'text-bg-warning',
                                    default                      => 'text-bg-info',
                                  };
                                ?><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($besoin['nom_categorie']) ?></span></td>
                                <td><?= number_format($besoin['quantite'], 2, ',', ' ') ?></td>
                                <td><?= number_format($besoin['montant_total'], 2, ',', ' ') ?> Ar</td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      <?php else: ?>
                        <p class="text-center text-secondary py-3 mb-0">Aucun besoin enregistré</p>
                      <?php endif; ?>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              <?php endforeach; ?>
            </div>
            <!--end::Row-->

            <?php if (empty($villes)): ?>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>Aucune ville enregistrée.
              </div>
            <?php endif; ?>
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
<?php require_once(__DIR__ . '/footer.php'); ?>
