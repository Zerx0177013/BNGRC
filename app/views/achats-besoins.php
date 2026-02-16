<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Achats via dons en argent</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Achats</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">

            <!-- Alert -->
            <div id="alertContainer"></div>

            <!-- Besoins restants -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="bi bi-clipboard-check me-2"></i>Besoins restants (Nature & Matériaux)
                </h3>
                <div class="card-tools">
                  <a href="<?= BASE_URL ?>/achats/liste" class="btn btn-primary btn-sm">
                    <i class="bi bi-list-check me-1"></i> Voir la liste des achats
                  </a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($besoins)): ?>
                  <div class="text-center text-muted py-4">
                    <i class="bi bi-check-circle display-4 d-block mb-2"></i>
                    <p class="lead">Tous les besoins sont satisfaits !</p>
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Ville</th>
                          <th>Article</th>
                          <th>Catégorie</th>
                          <th>Prix unitaire</th>
                          <th>Quantité restante</th>
                          <th>Montant HT</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($besoins as $besoin): ?>
                          <?php 
                            $montantHT = $besoin['quantite_restante'] * $besoin['prix_unitaire'];
                            $frais = $montantHT * ($config['valeur'] / 100);
                            $montantTotal = $montantHT + $frais;
                          ?>
                          <tr>
                            <td><?= $besoin['id_besoin'] ?></td>
                            <td><?= htmlspecialchars($besoin['nom_ville']) ?></td>
                            <td><?= htmlspecialchars($besoin['nom_article']) ?></td>
                            <td>
                              <span class="badge bg-secondary">
                                <?= htmlspecialchars($besoin['nom_categorie']) ?>
                              </span>
                            </td>
                            <td class="text-end"><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format($besoin['quantite_restante'], 2, ',', ' ') ?></td>
                            <td class="text-end fw-bold"><?= number_format($montantHT, 2, ',', ' ') ?> Ar</td>
                            <td class="text-center">
                              <button 
                                type="button" 
                                class="btn btn-success btn-sm btn-achat"
                                data-id-besoin="<?= $besoin['id_besoin'] ?>"
                                data-article="<?= htmlspecialchars($besoin['nom_article']) ?>"
                                data-ville="<?= htmlspecialchars($besoin['nom_ville']) ?>"
                                data-prix="<?= $besoin['prix_unitaire'] ?>"
                                data-quantite-max="<?= $besoin['quantite_restante'] ?>"
                                data-frais="<?= $config['valeur'] ?>"
                              >
                                <i class="bi bi-cart-plus me-1"></i> Acheter
                              </button>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <!-- Modal Achat -->
      <div class="modal fade" id="achatModal" tabindex="-1" aria-labelledby="achatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="achatModalLabel">
                <i class="bi bi-cart-plus me-2"></i>Effectuer un achat
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form id="achatForm">
              <div class="modal-body">
                <input type="hidden" id="id_besoin" name="id_besoin">
                <input type="hidden" id="frais_percent" name="frais_percent">
                
                <div class="mb-3">
                  <label class="form-label fw-bold">Besoin</label>
                  <p class="form-control-plaintext" id="besoinInfo"></p>
                </div>

                <div class="mb-3">
                  <label for="id_don" class="form-label">Don en argent <span class="text-danger">*</span></label>
                  <select class="form-select" id="id_don" name="id_don" required>
                    <option value="">-- Sélectionner un don --</option>
                    <?php foreach ($donsArgent as $don): ?>
                      <option 
                        value="<?= $don['id_don'] ?>"
                        data-montant="<?= $don['montant_restant'] ?>"
                      >
                        Don #<?= $don['id_don'] ?> - <?= htmlspecialchars($don['nom_article']) ?> (disponible: <?= number_format($don['montant_restant'], 2, ',', ' ') ?> Ar)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

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
                  >
                  <small class="form-text text-muted">Max: <span id="quantiteMax"></span></small>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Récapitulatif</label>
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <tbody>
                        <tr>
                          <td>Prix unitaire:</td>
                          <td class="text-end"><strong id="prixUnitaire">0,00 Ar</strong></td>
                        </tr>
                        <tr>
                          <td>Montant HT:</td>
                          <td class="text-end"><strong id="montantHT">0,00 Ar</strong></td>
                        </tr>
                        <tr>
                          <td>Frais (<span id="fraisLabel"></span>%):</td>
                          <td class="text-end"><strong id="montantFrais">0,00 Ar</strong></td>
                        </tr>
                        <tr class="table-active">
                          <td class="fw-bold">Montant total:</td>
                          <td class="text-end"><strong class="text-primary fs-5" id="montantTotal">0,00 Ar</strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div id="warningInsuffisant" class="alert alert-warning" style="display:none;">
                  <i class="bi bi-exclamation-triangle me-1"></i>
                  Le solde du don sélectionné est insuffisant.
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="bi bi-x-circle me-1"></i>Annuler
                </button>
                <button type="submit" class="btn btn-success" id="btnConfirmAchat">
                  <i class="bi bi-check-circle me-1"></i>Confirmer l'achat
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          var achatModal = new bootstrap.Modal(document.getElementById('achatModal'));
          var achatForm = document.getElementById('achatForm');
          var btnAchats = document.querySelectorAll('.btn-achat');

          var idBesoinInput = document.getElementById('id_besoin');
          var fraisPercentInput = document.getElementById('frais_percent');
          var besoinInfo = document.getElementById('besoinInfo');
          var idDonSelect = document.getElementById('id_don');
          var quantiteInput = document.getElementById('quantite');
          var quantiteMax = document.getElementById('quantiteMax');
          var prixUnitaireSpan = document.getElementById('prixUnitaire');
          var montantHTSpan = document.getElementById('montantHT');
          var montantFraisSpan = document.getElementById('montantFrais');
          var montantTotalSpan = document.getElementById('montantTotal');
          var fraisLabel = document.getElementById('fraisLabel');
          var warningInsuffisant = document.getElementById('warningInsuffisant');
          var btnConfirm = document.getElementById('btnConfirmAchat');

          var prixUnitaire = 0;
          var quantiteMaxVal = 0;
          var fraisPercent = 0;

          btnAchats.forEach(function (btn) {
            btn.addEventListener('click', function () {
              var idBesoin = btn.dataset.idBesoin;
              var article = btn.dataset.article;
              var ville = btn.dataset.ville;
              var prix = parseFloat(btn.dataset.prix);
              var qteMax = parseFloat(btn.dataset.quantiteMax);
              var frais = parseFloat(btn.dataset.frais);

              idBesoinInput.value = idBesoin;
              fraisPercentInput.value = frais;
              besoinInfo.textContent = ville + ' - ' + article;
              quantiteMax.textContent = qteMax.toFixed(2).replace('.', ',');
              quantiteInput.max = qteMax;
              quantiteInput.value = '';
              idDonSelect.value = '';
              fraisLabel.textContent = frais.toFixed(0);

              prixUnitaire = prix;
              quantiteMaxVal = qteMax;
              fraisPercent = frais;

              updateCalcul();
              achatModal.show();
            });
          });

          quantiteInput.addEventListener('input', updateCalcul);
          idDonSelect.addEventListener('change', updateCalcul);

          function updateCalcul() {
            var qte = parseFloat(quantiteInput.value) || 0;
            var montantHT = qte * prixUnitaire;
            var frais = montantHT * (fraisPercent / 100);
            var total = montantHT + frais;

            prixUnitaireSpan.textContent = prixUnitaire.toFixed(2).replace('.', ',') + ' Ar';
            montantHTSpan.textContent = montantHT.toFixed(2).replace('.', ',') + ' Ar';
            montantFraisSpan.textContent = frais.toFixed(2).replace('.', ',') + ' Ar';
            montantTotalSpan.textContent = total.toFixed(2).replace('.', ',') + ' Ar';

            var selectedOption = idDonSelect.options[idDonSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.montant) {
              var montantDispo = parseFloat(selectedOption.dataset.montant);
              if (total > montantDispo) {
                warningInsuffisant.style.display = '';
                btnConfirm.disabled = true;
              } else {
                warningInsuffisant.style.display = 'none';
                btnConfirm.disabled = false;
              }
            } else {
              warningInsuffisant.style.display = 'none';
              btnConfirm.disabled = false;
            }
          }

          achatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(achatForm);

            btnConfirm.disabled = true;
            btnConfirm.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> En cours...';

            fetch('<?= BASE_URL ?>/achats', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams(formData).toString()
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
              if (data.success) {
                showAlert('success', data.message);
                achatModal.hide();
                setTimeout(function () { window.location.reload(); }, 1500);
              } else {
                showAlert('danger', data.message);
                btnConfirm.disabled = false;
                btnConfirm.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmer l\'achat';
              }
            })
            .catch(function (err) {
              showAlert('danger', 'Erreur réseau: ' + err.message);
              btnConfirm.disabled = false;
              btnConfirm.innerHTML = '<i class="bi bi-check-circle me-1"></i>Confirmer l\'achat';
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
