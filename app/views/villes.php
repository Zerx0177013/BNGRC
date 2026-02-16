<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Gestion des villes</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Villes</li>
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
                  <i class="bi bi-geo-alt-fill me-2"></i>Liste des villes
                </h3>
                <a href="<?= BASE_URL ?>/villes/add" class="btn btn-primary btn-sm">
                  <i class="bi bi-plus-lg me-1"></i> Ajouter une ville
                </a>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th style="width: 60px">#</th>
                      <th>Nom de la ville</th>
                      <th>Région</th>
                      <th style="width: 180px" class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($villes)): ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                          <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                          Aucune ville enregistrée.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($villes as $index => $ville): ?>
                        <tr id="ville-row-<?= $ville['id_ville'] ?>">
                          <td><?= $index + 1 ?></td>
                          <td><?= htmlspecialchars($ville['nom_ville']) ?></td>
                          <td>
                            <span class="badge bg-info">
                              <?= htmlspecialchars($ville['nom_region'] ?? 'N/A') ?>
                            </span>
                          </td>
                          <td class="text-center">
                            <a href="<?= BASE_URL ?>/villes/<?= $ville['id_ville'] ?>/edit" 
                               class="btn btn-warning btn-sm me-1"
                               title="Modifier">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-danger btn-sm btn-delete"
                                    data-id="<?= $ville['id_ville'] ?>"
                                    data-name="<?= htmlspecialchars($ville['nom_ville']) ?>"
                                    title="Supprimer">
                              <i class="bi bi-trash"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <div class="card-footer text-muted">
                Total : <strong><?= count($villes) ?></strong> ville(s)
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
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="deleteModalLabel">
                <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              Voulez-vous vraiment supprimer la ville <strong id="deleteVilleName"></strong> ?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                <i class="bi bi-trash me-1"></i>Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          let deleteId = null;
          const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

          // Delete button click
          document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
              deleteId = this.dataset.id;
              document.getElementById('deleteVilleName').textContent = this.dataset.name;
              deleteModal.show();
            });
          });

          // Confirm delete
          document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (!deleteId) return;

            fetch('<?= BASE_URL ?>/villes/' + deleteId, {
              method: 'DELETE',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
              deleteModal.hide();
              if (data.success) {
                var row = document.getElementById('ville-row-' + deleteId);
                if (row) row.remove();
                showAlert('success', 'Ville supprimée avec succès.');
              } else {
                showAlert('danger', data.message || 'Erreur lors de la suppression.');
              }
            })
            .catch(function () {
              deleteModal.hide();
              showAlert('danger', 'Erreur réseau.');
            });
          });

          function showAlert(type, message) {
            var container = document.getElementById('alertContainer');
            container.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
              message +
              '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>' +
              '</div>';
          }
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
