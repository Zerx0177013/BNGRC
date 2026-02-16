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

                    <div class="card-footer">
                      <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>/regions" class="btn btn-secondary">
                          <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-check-circle"></i>
                          <?= isset($region) ? 'Enregistrer' : 'Ajouter' ?>
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
        var BASE_URL = '<?= BASE_URL ?>';
        var IS_EDIT = <?= isset($region) ? 'true' : 'false' ?>;
      </script>
      <script src="<?= BASE_URL ?>/public/assets/js/region-form.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
