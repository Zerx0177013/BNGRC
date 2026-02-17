<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Liste des achats</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/achats">Achats</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Liste</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">

            <!-- Alert -->
            <div id="alertContainer"></div>

            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                  <i class="bi bi-list-check me-2"></i>Historique des achats
                </h3>
                <div class="d-flex gap-2">
                  <select class="form-select form-select-sm" id="filtreVille" style="width: auto;">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                      <option value="<?= $ville['id_ville'] ?>" <?= (isset($selectedVille) && $selectedVille == $ville['id_ville']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ville['nom_ville']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <a href="<?= BASE_URL ?>/achats" class="btn btn-primary btn-sm">
                    <i class="bi bi-cart-plus me-1"></i> Nouvel achat
                  </a>
                </div>
              </div>
              <div class="card-body" id="achatsTableContainer">
                <!-- Le contenu sera généré dynamiquement -->
              </div>
              <div class="card-footer text-muted">
                Total : <strong id="totalCount">0</strong> achat(s)
              </div>
            </div>

          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <!-- Modal Confirmation Suppression -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">
                <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              Êtes-vous sûr de vouloir supprimer cet achat ?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-danger" id="btnConfirmDelete">Supprimer</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Données initiales pour JavaScript -->
      <script type="application/json" id="initialAchatsData">
        <?= json_encode($achats) ?>
      </script>
      
      <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
  <script src="<?= BASE_URL ?>/public/assets/js/achats-liste.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
