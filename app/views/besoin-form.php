<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0"><?= isset($besoin) ? 'Modifier le besoin' : 'Nouveau besoin' ?></h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/besoins">Besoins</a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?= isset($besoin) ? 'Modifier' : 'Ajouter' ?>
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
              <div class="col-md-8 mx-auto">
                <div class="card card-primary card-outline mb-4">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="bi bi-clipboard-plus me-2"></i>
                      <?= isset($besoin) ? 'Modifier le besoin #' . $besoin['id_besoin'] : 'Saisir un nouveau besoin' ?>
                    </h3>
                  </div>

                  <form 
                    id="besoinForm"
                    method="POST" 
                    action="<?= isset($besoin) ? BASE_URL . '/besoins/' . $besoin['id_besoin'] : BASE_URL . '/besoins' ?>"
                  >
                    <div class="card-body">

                      <!-- Ville -->
                      <div class="mb-3">
                        <label for="id_ville" class="form-label">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_ville" name="id_ville" required>
                          <option value="">-- Sélectionner une ville --</option>
                          <?php foreach ($villes as $ville): ?>
                            <option 
                              value="<?= $ville['id_ville'] ?>"
                              <?= (isset($besoin) && $besoin['id_ville'] == $ville['id_ville']) ? 'selected' : '' ?>
                            >
                              <?= htmlspecialchars($ville['nom_ville']) ?> (<?= htmlspecialchars($ville['nom_region']) ?>)
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <!-- Article -->
                      <div class="mb-3">
                        <label for="id_article" class="form-label">Article <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_article" name="id_article" required>
                          <option value="">-- Sélectionner un article --</option>
                          <?php foreach ($articles as $article): ?>
                            <option 
                              value="<?= $article['id_article'] ?>"
                              data-prix="<?= $article['prix_unitaire'] ?>"
                              <?= (isset($besoin) && $besoin['id_article'] == $article['id_article']) ? 'selected' : '' ?>
                            >
                              <?= htmlspecialchars($article['nom_article']) ?> — <?= htmlspecialchars($article['nom_categorie']) ?> (<?= number_format($article['prix_unitaire'], 2, ',', ' ') ?> Ar)
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
                          value="<?= isset($besoin) ? $besoin['quantite'] : '' ?>"
                          placeholder="Ex: 100"
                        >
                      </div>

                      <!-- Prix unitaire (lecture seule) -->
                      <div class="mb-3">
                        <label class="form-label">Prix unitaire</label>
                        <input 
                          type="text" 
                          class="form-control" 
                          id="prix_unitaire_display" 
                          readonly 
                          disabled
                          value="<?= isset($besoin) ? number_format($besoin['prix_unitaire'], 2, ',', ' ') . ' Ar' : '' ?>"
                        >
                      </div>

                      <!-- Montant total (lecture seule) -->
                      <div class="mb-3">
                        <label class="form-label">Montant total estimé</label>
                        <input 
                          type="text" 
                          class="form-control fw-bold text-success" 
                          id="montant_total_display" 
                          readonly 
                          disabled
                          value="<?= isset($besoin) ? number_format($besoin['quantite'] * $besoin['prix_unitaire'], 2, ',', ' ') . ' Ar' : '' ?>"
                        >
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-between">
                      <a href="<?= BASE_URL ?>/besoins" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                      </a>
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        <?= isset($besoin) ? 'Enregistrer les modifications' : 'Ajouter le besoin' ?>
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
          const articleSelect = document.getElementById('id_article');
          const quantiteInput = document.getElementById('quantite');
          const prixDisplay = document.getElementById('prix_unitaire_display');
          const montantDisplay = document.getElementById('montant_total_display');

          function updateMontant() {
            const selected = articleSelect.options[articleSelect.selectedIndex];
            const prix = selected ? parseFloat(selected.dataset.prix) || 0 : 0;
            const qte = parseFloat(quantiteInput.value) || 0;

            prixDisplay.value = prix > 0 ? prix.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' Ar' : '';
            montantDisplay.value = (prix * qte) > 0 ? (prix * qte).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' Ar' : '';
          }

          articleSelect.addEventListener('change', updateMontant);
          quantiteInput.addEventListener('input', updateMontant);

          // Form submission via fetch
          document.getElementById('besoinForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const isEdit = <?= isset($besoin) ? 'true' : 'false' ?>;
            const url = form.action;

            fetch(url, {
              method: isEdit ? 'PUT' : 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams(formData).toString()
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                window.location.href = '<?= BASE_URL ?>/besoins';
              } else {
                alert(data.message || 'Erreur lors de l\'enregistrement.');
              }
            })
            .catch(() => alert('Erreur réseau.'));
          });
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
