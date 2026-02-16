<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><?= isset($ville) ? 'Modifier la ville' : 'Nouvelle ville' ?></h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/villes">Villes</a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?= isset($ville) ? 'Modifier' : 'Ajouter' ?>
                  </li>
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
              <div class="col-md-6 mx-auto">
                <div class="card card-primary card-outline mb-4">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="bi bi-geo-alt me-2"></i>
                      <?= isset($ville) ? 'Modifier la ville #' . $ville['id_ville'] : 'Ajouter une ville' ?>
                    </h3>
                  </div>

                  <form 
                    id="villeForm"
                    method="POST" 
                    action="<?= isset($ville) ? BASE_URL . '/villes/' . $ville['id_ville'] : BASE_URL . '/villes' ?>"
                  >
                    <div class="card-body">

                      <!-- Nom de la ville -->
                      <div class="mb-3">
                        <label for="name" class="form-label">Nom de la ville <span class="text-danger">*</span></label>
                        <input 
                          type="text" 
                          class="form-control" 
                          id="name" 
                          name="name" 
                          required
                          value="<?= isset($ville) ? htmlspecialchars($ville['nom_ville']) : '' ?>"
                          placeholder="Ex: Antananarivo"
                        >
                      </div>

                      <!-- Région -->
                      <div class="mb-3">
                        <label for="id_region" class="form-label">Région <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_region" name="id_region" required>
                          <option value="">-- Sélectionner une région --</option>
                          <?php foreach ($regions as $region): ?>
                            <option 
                              value="<?= $region['id_region'] ?>"
                              <?= (isset($ville) && $ville['id_region'] == $region['id_region']) ? 'selected' : '' ?>
                            >
                              <?= htmlspecialchars($region['nom_region']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>/villes" class="btn btn-secondary">
                          <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-check-circle"></i>
                          <?= isset($ville) ? 'Enregistrer' : 'Ajouter' ?>
                        </button>
                      </div>
                    </div>
                  </form>

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
          document.getElementById('villeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = e.target;
            var formData = new FormData(form);
            var isEdit = <?= isset($ville) ? 'true' : 'false' ?>;
            var url = form.action;

            fetch(url, {
              method: isEdit ? 'PUT' : 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams(formData).toString()
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
              if (data.success) {
                window.location.href = '<?= BASE_URL ?>/villes';
              } else {
                alert(data.message || 'Erreur lors de l\'enregistrement.');
              }
            })
            .catch(function () { alert('Erreur réseau.'); });
          });
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
