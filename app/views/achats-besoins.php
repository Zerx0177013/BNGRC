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
                            <td><?= e($besoin['nom_ville']) ?></td>
                            <td><?= e($besoin['nom_article']) ?></td>
                            <td><?php
                              $cat = strtolower($besoin['nom_categorie']);
                              $textClass = match(true) {
                                str_contains($cat, 'argent') => 'text-success',
                                str_contains($cat, 'mat')    => 'text-warning',
                                default                      => 'text-info',
                              };
                            ?><span class="<?= $textClass ?> fw-semibold"><?= e($besoin['nom_categorie']) ?></span></td>
                            <td class="text-end"><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format($besoin['quantite_restante'], 2, ',', ' ') ?></td>
                            <td class="text-end fw-bold"><?= number_format($montantHT, 2, ',', ' ') ?> Ar</td>
                            <td class="text-center">
                              <button 
                                type="button" 
                                class="btn btn-success btn-sm btn-achat"
                                data-id-besoin="<?= $besoin['id_besoin'] ?>"
                                data-article="<?= e($besoin['nom_article']) ?>"
                                data-ville="<?= e($besoin['nom_ville']) ?>"
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
                        Don #<?= $don['id_don'] ?> - <?= e($don['nom_article']) ?> (disponible: <?= number_format($don['montant_restant'], 2, ',', ' ') ?> Ar)
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

      <script>var BASE_URL = '<?= BASE_URL ?>';</script>
  <script src="<?= BASE_URL ?>/public/assets/js/achats-besoins.js"></script>

<?php include __DIR__ . '/footer.php'; ?>
