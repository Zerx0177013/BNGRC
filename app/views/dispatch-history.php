<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Historique des dispatches</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dispatch">Dispatch</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Historique</li>
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
                  <i class="bi bi-clock-history me-2"></i>Historique des attributions
                </h3>
                <div>
                  <a href="<?= BASE_URL ?>/dispatch" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                  </a>
                  <?php if (!empty($dispatches)): ?>
                    <button type="button" class="btn btn-danger btn-sm" id="btnClearAll">
                      <i class="bi bi-trash me-1"></i> Réinitialiser
                    </button>
                  <?php endif; ?>
                </div>
              </div>
              <div class="card-body">
                
                <?php if (empty($dispatches)): ?>
                  <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Aucun dispatch enregistré.
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                      <thead class="table-dark">
                        <tr>
                          <th style="width: 60px">#</th>
                          <th>Article</th>
                          <th>Ville</th>
                          <th>Don #</th>
                          <th>Besoin #</th>
                          <th>Quantité attribuée</th>
                          <th>Date dispatch</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($dispatches as $dispatch): ?>
                          <tr>
                            <td><?= $dispatch['id_dispatch'] ?></td>
                            <td><?= htmlspecialchars($dispatch['nom_article']) ?></td>
                            <td>
                              <span class="badge bg-info">
                                <?= htmlspecialchars($dispatch['nom_ville']) ?>
                              </span>
                            </td>
                            <td><?= $dispatch['id_don'] ?></td>
                            <td><?= $dispatch['id_besoin'] ?></td>
                            <td>
                              <span class="badge bg-success">
                                <?= number_format($dispatch['quantite_attribuee'], 2, ',', ' ') ?>
                              </span>
                            </td>
                            <td><?= date('d/m/Y H:i:s', strtotime($dispatch['date_dispatch'])) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>

              </div>
              <div class="card-footer text-muted">
                Total : <strong><?= count($dispatches) ?></strong> attribution(s)
              </div>
            </div>

          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <!-- Modal Confirmation -->
      <div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="clearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="clearModalLabel">
                <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la réinitialisation
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <p>Voulez-vous vraiment supprimer <strong>tous les dispatches</strong> ?</p>
              <p class="text-danger mb-0">
                <i class="bi bi-exclamation-circle me-1"></i>
                Cette action est irréversible.
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-danger" id="confirmClearBtn">
                <i class="bi bi-trash me-1"></i>Réinitialiser
              </button>
            </div>
          </div>
        </div>
      </div>

      <script>var BASE_URL = '<?= BASE_URL ?>';</script>
      <script src="<?= BASE_URL ?>/public/assets/js/dispatch-history.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
