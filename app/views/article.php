<?php
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';

$catColors = [
    'nature'     => ['bg' => 'bg-info',    'textbg' => 'text-bg-info',    'icon' => 'bi bi-tree-fill', 'label' => 'Nature'],
    'materiel'   => ['bg' => 'bg-warning',  'textbg' => 'text-bg-warning',  'icon' => 'bi bi-tools', 'label' => 'Matériel'],
    'argent'     => ['bg' => 'bg-success',  'textbg' => 'text-bg-success',  'icon' => 'bi bi-cash-stack', 'label' => 'Argent'],
];

$countByCategorie = [];
$articlesByCategorie = [];
foreach ($articles as $art) {
    $catName = $art['nom_categorie'] ?? 'Inconnu';
    $catId = $art['id_categorie'];
    if (!isset($countByCategorie[$catName])) {
        $countByCategorie[$catName] = 0;
    }
    $countByCategorie[$catName]++;
    $articlesByCategorie[$catName][] = $art;
}
?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Articles</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Articles</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <div class="app-content">
          <div class="container-fluid">

            <!-- Bouton ajouter article -->
            <div class="row mb-3">
              <div class="col-12">
                <a href="<?= BASE_URL ?>/articles/add" class="btn btn-primary">
                  <i class="bi bi-plus-circle me-1"></i> Ajouter un article
                </a>
              </div>
            </div>

            <!-- ========== CATÉGORIES (3 info-boxes normales en haut) ========== -->
            <div class="row">
              <?php foreach ($countByCategorie as $catName => $count):
                $style = $catColors[$catName] ?? ['bg' => 'bg-secondary', 'textbg' => 'text-bg-secondary', 'icon' => 'bi bi-question-circle'];
              ?>
              <div class="col-md-4">
                <div class="info-box">
                  <span class="info-box-icon <?= $style['textbg'] ?> shadow-sm">
                    <i class="<?= $style['icon'] ?>"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text"><?= htmlspecialchars($style['label'] ?? ucfirst($catName)) ?></span>
                    <span class="info-box-number"><?= $count ?> <small>article<?= $count > 1 ? 's' : '' ?></small></span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <!-- ========== FIN CATÉGORIES ========== -->

            <!-- ========== ARTICLES par catégorie ========== -->
            <?php foreach ($articlesByCategorie as $catName => $catArticles):
              $style = $catColors[$catName] ?? ['bg' => 'bg-secondary', 'textbg' => 'text-bg-secondary', 'icon' => 'bi bi-question-circle'];
            ?>
            <div class="row mt-4">
              <div class="col-12">
                <h5 class="mb-3">
                  <span class="badge <?= $style['bg'] ?>">
                    <i class="<?= $style['icon'] ?> me-1"></i>
                    <?= htmlspecialchars($style['label'] ?? ucfirst($catName)) ?>
                  </span>
                </h5>
              </div>
            </div>
            <div class="row">
              <?php foreach ($catArticles as $art): ?>
              <div class="col-lg-4 col-md-6">
                <div class="card mb-3 shadow-sm border-start border-4 border-<?= str_replace('bg-', '', $style['bg']) ?>">
                  <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                      <span class="info-box-icon <?= $style['textbg'] ?> shadow-sm me-3" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;border-radius:.375rem;">
                        <i class="<?= $style['icon'] ?>"></i>
                      </span>
                      <div class="flex-grow-1">
                        <h6 class="mb-1"><?= htmlspecialchars($art['nom_article']) ?></h6>
                        <span class="text-muted fw-bold"><?= number_format($art['prix_unitaire'], 0, ',', ' ') ?> <small>Ar</small></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <!-- ========== FIN ARTICLES ========== -->

          </div>
        </div>
      </main>
      <!--end::App Main-->
<?php include __DIR__ . '/footer.php'; ?>
