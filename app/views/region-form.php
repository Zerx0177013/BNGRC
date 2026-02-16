<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><?= isset($region) ? 'Modifier la région' : 'Nouvelle région' ?></h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/regions">Régions</a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?= isset($region) ? 'Modifier' : 'Ajouter' ?>
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
                      <i class="bi bi-map me-2"></i>
                      <?= isset($region) ? 'Modifier la région #' . $region['id_region'] : 'Ajouter une région' ?>
                    </h3>
                  </div>

                  <form 
                    id="regionForm"
                    method="POST" 
                    action="<?= isset($region) ? BASE_URL . '/regions/' . $region['id_region'] : BASE_URL . '/regions' ?>"
                  >
                    <div class="card-body">

                      <!-- Nom de la région -->
                      <div class="mb-3">
                        <label for="name" class="form-label">Nom de la région <span class="text-danger">*</span></label>
                        <input 
                          type="text" 
                          class="form-control" 
                          id="name" 
                          name="name" 
                          required
                          value="<?= isset($region) ? htmlspecialchars($region['nom_region']) : '' ?>"
                          placeholder="Ex: Analamanga"
                        >
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-between">
                      <a href="<?= BASE_URL ?>/regions" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                      </a>
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        <?= isset($region) ? 'Enregistrer les modifications' : 'Ajouter la région' ?>
                      </button>
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
          document.getElementById('regionForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = e.target;
            var formData = new FormData(form);
            var isEdit = <?= isset($region) ? 'true' : 'false' ?>;
            var url = form.action;

            fetch(url, {
              method: isEdit ? 'PUT' : 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams(formData).toString()
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
              if (data.success) {
                window.location.href = '<?= BASE_URL ?>/regions';
              } else {
                alert(data.message || 'Erreur lors de l\'enregistrement.');
              }
            })
            .catch(function () { alert('Erreur réseau.'); });
          });
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
