<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="bi bi-clipboard-data"></i> Récapitulation</h1>
        </div>
        <div class="col-sm-6">
          <button type="button" class="btn btn-primary float-end" id="btnActualiser">
            <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
            <span class="spinner-border spinner-border-sm d-none" id="refreshSpinner"></span>
            Actualiser
          </button>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <!-- Row des 3 cards -->
      <div class="row">
        <!-- Card 1: Besoins totaux -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="bi bi-calculator"></i> Besoins totaux
              </h3>
            </div>
            <div class="card-body">
              <h2 class="text-primary mb-0" id="montantTotal">
                <?= number_format($data['montant_total'] ?? 0, 2, ',', ' ') ?> Ar
              </h2>
              <p class="text-muted small mb-0">Montant total des besoins</p>
            </div>
          </div>
        </div>

        <!-- Card 2: Besoins satisfaits -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="bi bi-check-circle"></i> Besoins satisfaits
              </h3>
            </div>
            <div class="card-body">
              <h2 class="text-success mb-1" id="montantSatisfait">
                <?= number_format($data['montant_satisfait'] ?? 0, 2, ',', ' ') ?> Ar
              </h2>
              <div>
                <span class="badge bg-success" id="pourcentageSatisfait">
                  <?= number_format($data['pourcentage_satisfait'] ?? 0, 2, ',', ' ') ?>%
                </span>
                <span class="text-muted small">du total</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3: Besoins restants -->
        <div class="col-lg-4 col-md-6">
          <div class="card card-danger card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="bi bi-exclamation-circle"></i> Besoins restants
              </h3>
            </div>
            <div class="card-body">
              <h2 class="text-danger mb-0" id="montantRestant">
                <?= number_format($data['montant_restant'] ?? 0, 2, ',', ' ') ?> Ar
              </h2>
              <p class="text-muted small mb-0">À satisfaire</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tableau détaillé par catégorie (optionnel) -->
      <div class="row mt-3">
        <div class="col-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">
                <i class="bi bi-bar-chart"></i> Détails par catégorie
              </h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                  <thead class="table-dark">
                    <tr>
                      <th>Catégorie</th>
                      <th>Besoins totaux</th>
                      <th>Satisfaits</th>
                      <th>Restants</th>
                      <th>Progression</th>
                    </tr>
                  </thead>
                  <tbody id="recapTableBody">
                    <?php if (isset($recap) && count($recap) > 0): ?>
                      <?php foreach ($recap as $row): ?>
                        <tr>
                          <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($row['nom_categorie']) ?></span>
                          </td>
                          <td class="text-end"><?= number_format($row['montant_total'], 2, ',', ' ') ?> Ar</td>
                          <td class="text-end text-success"><?= number_format($row['montant_satisfait'], 2, ',', ' ') ?> Ar</td>
                          <td class="text-end text-danger"><?= number_format($row['montant_restant'], 2, ',', ' ') ?> Ar</td>
                          <td>
                            <div class="progress">
                              <div class="progress-bar bg-success" role="progressbar" 
                                   style="width: <?= $row['pourcentage_satisfait'] ?>%"
                                   aria-valuenow="<?= $row['pourcentage_satisfait'] ?>" 
                                   aria-valuemin="0" 
                                   aria-valuemax="100">
                                <?= number_format($row['pourcentage_satisfait'], 1, ',', ' ') ?>%
                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center text-muted">Aucune donnée disponible</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php include 'footer.php'; ?>

<script>var BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/assets/js/recap.js"></script>
