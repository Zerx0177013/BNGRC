<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Nouvel article</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/articles">Articles</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
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
                      <i class="bi bi-box-seam me-2"></i>
                      Ajouter un nouvel article
                    </h3>
                  </div>

                  <form 
                    id="articleForm"
                    method="POST" 
                    action="<?= BASE_URL ?>/articles"
                  >
                    <div class="card-body">

                      <!-- Nom de l'article -->
                      <div class="mb-3">
                        <label for="name" class="form-label">Nom de l'article <span class="text-danger">*</span></label>
                        <input 
                          type="text" 
                          class="form-control" 
                          id="name" 
                          name="name" 
                          required
                          placeholder="Ex: Riz, Eau potable, Couverture"
                        >
                      </div>

                      <!-- Catégorie -->
                      <div class="mb-3">
                        <label for="id_categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_categorie" name="id_categorie" required>
                          <option value="">-- Sélectionner une catégorie --</option>
                          <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categorie'] ?>">
                              <?= htmlspecialchars($cat['nom_categorie']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <!-- Prix unitaire -->
                      <div class="mb-3">
                        <label for="prix" class="form-label">Prix unitaire (Ar) <span class="text-danger">*</span></label>
                        <input 
                          type="number" 
                          class="form-control" 
                          id="prix" 
                          name="prix" 
                          step="0.01" 
                          min="0" 
                          required
                          placeholder="Ex: 5000"
                        >
                        <div class="form-text">
                          <i class="bi bi-info-circle me-1"></i>
                          Le prix est en Ariary (Ar)
                        </div>
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary">
                          <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-check-circle"></i>
                          Ajouter l'article
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
      </script>
      <script src="<?= BASE_URL ?>/public/assets/js/article-form.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
