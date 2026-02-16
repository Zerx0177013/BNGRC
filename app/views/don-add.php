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
        <!--end::App Content Header-->

        <!--begin::App Content-->
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

                      <!-- Article -->
                      <div class="mb-3">
                        <label for="id_article" class="form-label">Article <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_article" name="id_article" required>
                          <option value="">-- Sélectionner un article --</option>
                          <?php foreach ($articles as $article): ?>
                            <option 
                              value="<?= $article['id_article'] ?>"
                            >
                              <?= htmlspecialchars($article['nom_article']) ?> — <?= htmlspecialchars($article['nom_categorie']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
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

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const articleSelect = document.getElementById('id_article');
          const besoinSelect = document.getElementById('id_besoin');

          // Filtrer les besoins selon l'article sélectionné
          function filterBesoins() {
            const selectedArticleId = articleSelect.value;
            const besoinOptions = besoinSelect.querySelectorAll('option');

            besoinOptions.forEach((option, index) => {
              if (index === 0) return; // Skip "Sélectionner un besoin"

              const besoinArticleId = option.dataset.articleId;
              
              if (!selectedArticleId || besoinArticleId === selectedArticleId) {
                option.style.display = '';
              } else {
                option.style.display = 'none';
              }
            });

            // Reset la sélection si le besoin ne correspond plus à l'article
            const selectedBesoin = besoinSelect.options[besoinSelect.selectedIndex];
            if (selectedBesoin && selectedBesoin.dataset.articleId !== selectedArticleId && selectedArticleId) {
              besoinSelect.value = '';
            }
          }

          // Filtrer quand l'article change
          articleSelect.addEventListener('change', filterBesoins);

          // Filtrer au chargement si un article est déjà sélectionné
          filterBesoins();

          // Form submission via fetch
          document.getElementById('donForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const url = form.action;

            fetch(url, {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams(formData).toString()
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                alert('Don enregistré avec succès !');
                form.reset();
                filterBesoins(); // Reset le filtre après reset du formulaire
              } else {
                alert(data.message || 'Erreur lors de l\'enregistrement du don.');
              }
            })
            .catch(() => alert('Erreur réseau.'));
          });
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
