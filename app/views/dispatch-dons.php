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
           
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                  <i class="bi bi-gift-fill me-2"></i>Dons disponibles
                </h3>
                <div>
                  <a href="<?= BASE_URL ?>/dispatch/history" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-clock-history me-1"></i> Historique
                  </a>
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
          var selectedCount = document.getElementById('selectedCount');

          function updateUI() {
            var checkedCount = document.querySelectorAll('.don-checkbox:checked').length;
            btnDispatch.disabled = checkedCount === 0;
            
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

          if (btnDispatch) {
            btnDispatch.addEventListener('click', function () {
              var selected = Array.from(document.querySelectorAll('.don-checkbox:checked'))
                .map(function (cb) { return cb.value; });

              if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un don.');
                return;
              }

              if (!confirm('Voulez-vous dispatcher ' + selected.length + ' don(s) ?')) {
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
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
