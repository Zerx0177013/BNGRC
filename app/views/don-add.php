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
                              data-nom="<?= htmlspecialchars($article['nom_article']) ?>"
                              data-categorie="<?= htmlspecialchars($article['nom_categorie']) ?>"
                            >
                              <?= htmlspecialchars($article['nom_article']) ?> — <?= htmlspecialchars($article['nom_categorie']) ?> (<?= number_format($article['prix_unitaire'], 2, ',', ' ') ?> Ar)
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Choisissez l'article que vous souhaitez donner</small>
                      </div>

                      <!-- Besoin associé -->
                      <div class="mb-3">
                        <label for="id_besoin" class="form-label">Besoin associé (optionnel)</label>
                        <select class="form-select" id="id_besoin" name="id_besoin">
                          <option value="">-- Aucun besoin spécifique --</option>
                          <?php foreach ($besoins as $besoin): ?>
                            <option 
                              value="<?= $besoin['id_besoin'] ?>"
                              data-ville="<?= htmlspecialchars($besoin['nom_ville']) ?>"
                              data-article="<?= htmlspecialchars($besoin['nom_article']) ?>"
                              data-quantite="<?= $besoin['quantite'] ?>"
                              data-id-article="<?= $besoin['id_article'] ?>"
                            >
                              <?= htmlspecialchars($besoin['nom_ville']) ?> — <?= htmlspecialchars($besoin['nom_article']) ?> (<?= number_format($besoin['quantite'], 2, ',', ' ') ?> requis)
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Vous pouvez associer ce don à un besoin spécifique d'une ville</small>
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
                        <small class="text-muted" id="quantite_besoin_hint"></small>
                      </div>

                      <!-- Date du don (optionnel) -->
                      <div class="mb-3">
                        <label for="date_don" class="form-label">Date du don (optionnel)</label>
                        <input 
                          type="datetime-local" 
                          class="form-control" 
                          id="date_don" 
                          name="date_don"
                        >
                        <small class="text-muted">Si non renseigné, la date actuelle sera utilisée</small>
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-between">
                      <a href="<?= BASE_URL ?>/dons" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                      </a>
                      <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>
                        Enregistrer le don
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
          const besoinSelect = document.getElementById('id_besoin');
          const quantiteInput = document.getElementById('quantite');
          const quantiteHint = document.getElementById('quantite_besoin_hint');

          // Filtrer les besoins en fonction de l'article sélectionné
          function filterBesoins() {
            const selectedArticleId = articleSelect.value;
            const besoinOptions = besoinSelect.querySelectorAll('option');

            besoinOptions.forEach((option, index) => {
              if (index === 0) return; // Skip the first "Aucun besoin" option

              const optionArticleId = option.dataset.idArticle;
              if (!selectedArticleId || optionArticleId === selectedArticleId) {
                option.style.display = '';
              } else {
                option.style.display = 'none';
              }
            });

            // Reset besoin selection if it doesn't match the article
            const selectedBesoinOption = besoinSelect.options[besoinSelect.selectedIndex];
            if (selectedBesoinOption && selectedBesoinOption.dataset.idArticle !== selectedArticleId && selectedArticleId) {
              besoinSelect.value = '';
              updateQuantiteHint();
            }
          }

          // Afficher un indice pour la quantité basé sur le besoin sélectionné
          function updateQuantiteHint() {
            const selectedOption = besoinSelect.options[besoinSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
              const quantiteBesoin = selectedOption.dataset.quantite;
              const ville = selectedOption.dataset.ville;
              quantiteHint.textContent = `💡 Le besoin pour ${ville} est de ${parseFloat(quantiteBesoin).toLocaleString('fr-FR', { minimumFractionDigits: 2 })} unités`;
              quantiteHint.classList.add('text-info');
            } else {
              quantiteHint.textContent = '';
            }
          }

          // Ajouter la recherche simple dans les dropdowns
          function makeSearchable(selectElement) {
            selectElement.addEventListener('keypress', function(e) {
              const char = e.key.toLowerCase();
              const options = Array.from(selectElement.options);
              const currentIndex = selectElement.selectedIndex;
              
              for (let i = currentIndex + 1; i < options.length; i++) {
                if (options[i].text.toLowerCase().startsWith(char) && options[i].style.display !== 'none') {
                  selectElement.selectedIndex = i;
                  if (selectElement === besoinSelect) {
                    updateQuantiteHint();
                  }
                  break;
                }
              }
            });
          }

          // Initialize search functionality
          makeSearchable(articleSelect);
          makeSearchable(besoinSelect);

          // Event listeners
          articleSelect.addEventListener('change', filterBesoins);
          besoinSelect.addEventListener('change', updateQuantiteHint);

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
                window.location.href = '<?= BASE_URL ?>/dons';
              } else {
                alert(data.message || 'Erreur lors de l\'enregistrement du don.');
              }
            })
            .catch(() => alert('Erreur réseau.'));
          });
        });
      </script>

<?php include __DIR__ . '/footer.php'; ?>
