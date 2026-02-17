<?php
include __DIR__ . '/header.php';

$catColors = [
    'Nature'     => ['bg' => 'bg-info',    'textbg' => 'text-bg-info',    'icon' => 'bi bi-tree-fill'],
    'Matériaux'  => ['bg' => 'bg-warning',  'textbg' => 'text-bg-warning',  'icon' => 'bi bi-tools'],
    'Argent'     => ['bg' => 'bg-success',  'textbg' => 'text-bg-success',  'icon' => 'bi bi-cash-stack'],
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
                    <span class="info-box-text"><?= e($catName) ?></span>
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
            <h5 class="mt-3 mb-2">
              <span class="badge <?= $style['bg'] ?>"><?= e($catName) ?></span>
            </h5>
            <div class="row">
              <?php foreach ($catArticles as $art): ?>
              <div class="col-lg-4 col-md-6">
                <div class="card mb-3 border-start border-4 border-<?= str_replace('bg-', '', $style['bg']) ?>">
                  <div class="card-body p-3 d-flex align-items-center">
                    <span class="info-box-icon <?= $style['textbg'] ?> shadow-sm me-3" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;border-radius:.375rem;">
                      <i class="<?= $style['icon'] ?>"></i>
                    </span>
                    <div>
                      <h6 class="mb-0"><?= e($art['nom_article']) ?></h6>
                      <span class="text-muted"><?= number_format($art['prix_unitaire'], 0, ',', ' ') ?> <small>Ar</small></span>
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
