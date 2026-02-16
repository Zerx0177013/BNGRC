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
          <div class="container-fluid">
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
                    <div class="card-body p-0">
                      <?php if (!empty($ville['besoins'])): ?>
                        <table class="table table-striped align-middle mb-0">
                          <thead>
                            <tr>
                              <th style="width: 50px;">#</th>
                              <th>Article</th>
                              <th>Catégorie</th>
                              <th>Quantité</th>
                              <th>Prix unitaire</th>
                              <th>Montant total</th>
                              <th>Date de saisie</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($ville['besoins'] as $besoin): ?>
                              <tr>
                                <td><?= $besoin['id_besoin'] ?></td>
                                <td><?= htmlspecialchars($besoin['nom_article']) ?></td>
                                <td>
                                  <span class="badge text-bg-info"><?= htmlspecialchars($besoin['nom_categorie']) ?></span>
                                </td>
                                <td><?= number_format($besoin['quantite'], 2, ',', ' ') ?></td>
                                <td><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                <td><?= number_format($besoin['montant_total'], 2, ',', ' ') ?> Ar</td>
                                <td><?= date('d/m/Y H:i', strtotime($besoin['date_saisie'])) ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      <?php else: ?>
                        <p class="text-center text-secondary py-3 mb-0">Aucun besoin enregistré pour cette ville</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <?php if (empty($villes)): ?>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>Aucune ville enregistrée.
              </div>
            <?php endif; ?>
          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
<?php require_once(__DIR__ . '/footer.php'); ?>
