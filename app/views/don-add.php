<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Nouveau don</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dons">Dons</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-8 mx-auto">
                <div class="card card-success card-outline mb-4">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="bi bi-gift me-2"></i>
                      Enregistrer un nouveau don
                    </h3>
                  </div>

                  <form 
                    id="donForm"
                    method="POST" 
                    action="<?= BASE_URL ?>/dons"
                  >
                    <div class="card-body">

                      <!-- Info -->
                      <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i>
                        Seuls les articles ayant des besoins non satisfaits sont affichés. 
                        Un don pour un article sans besoin en attente ne pourra pas être dispatché.
                      </div>

                      <!-- Article -->
                      <div class="mb-3">
                        <label for="id_article" class="form-label">Article <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_article" name="id_article" required>
                          <option value="">-- Sélectionner un article --</option>
                          <?php foreach ($articles as $article): ?>
                            <option 
                              value="<?= $article['id_article'] ?>"
                              data-villes="<?= htmlspecialchars($article['villes_besoins']) ?>"
                              data-besoin-restant="<?= $article['quantite_besoin_restante'] ?>"
                            >
                              <?= htmlspecialchars($article['nom_article']) ?> — <?= htmlspecialchars($article['nom_categorie']) ?> (besoin restant: <?= number_format($article['quantite_besoin_restante'], 2, ',', ' ') ?>)
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <div id="villesDetail" class="form-text text-muted mt-1" style="display:none;">
                          <i class="bi bi-geo-alt me-1"></i><span id="villesText"></span>
                        </div>
                      </div>

                      <!-- Besoin associé -->
                      <div class="mb-3">
                        <label for="id_besoin" class="form-label">Besoin associé</label>
                        <select class="form-select" id="id_besoin" name="id_besoin">
                          <option value="">-- Sélectionner un besoin --</option>
                          <?php foreach ($besoins as $besoin): ?>
                            <option 
                              value="<?= $besoin['id_besoin'] ?>"
                              data-article-id="<?= $besoin['id_article'] ?>"
                            >
                              <?= htmlspecialchars($besoin['nom_ville']) ?> — <?= htmlspecialchars($besoin['nom_article']) ?> (<?= number_format($besoin['quantite'], 2) ?> requis)
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <!-- Quantité -->
                      <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input 
                          type="number" 
                          class="form-control" 
                          id="quantite" 
                          name="quantite" 
                          step="0.01" 
                          min="0.01" 
                          required
                          placeholder="Ex: 100"
                        >
                        <div id="quantiteWarning" class="alert alert-warning mt-2" style="display:none;">
                          <i class="bi bi-exclamation-triangle me-1"></i>
                          <span id="quantiteWarningText"></span>
                        </div>
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>/dons" class="btn btn-secondary">
                          <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-success">
                          <i class="bi bi-check-circle"></i> Enregistrer le don
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

      <script>var BASE_URL = '<?= BASE_URL ?>';</script>
  <script src="<?= BASE_URL ?>/public/assets/js/don-add.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
