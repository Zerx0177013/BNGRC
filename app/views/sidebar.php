      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="<?= BASE_URL ?>/" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="<?= BASE_URL ?>/public/assets/img/AdminLTELogo.png"
              alt="BNGRC Logo"
              class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">BNGRC</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation">
              <li class="nav-header">MENU PRINCIPAL</li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/" class="nav-link <?= (isset($currentPage) && $currentPage === 'dashboard') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-header">GESTION</li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/regions" class="nav-link <?= (isset($currentPage) && $currentPage === 'regions') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-map-fill"></i>
                  <p>Régions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/villes" class="nav-link <?= (isset($currentPage) && $currentPage === 'villes') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-geo-alt-fill"></i>
                  <p>Villes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/articles" class="nav-link <?= (isset($currentPage) && $currentPage === 'articles') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Articles</p>
                </a>
              </li>

              <li class="nav-header">SINISTRÉS</li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/besoins/add" class="nav-link <?= (isset($currentPage) && $currentPage === 'besoins') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-clipboard-plus"></i>
                  <p>Besoins</p>
                </a>
              </li>
              <li class="nav-header">DONATION</li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/dons" class="nav-link <?= (isset($currentPage) && $currentPage === 'dons') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-heart-fill"></i>
                  <p>Dons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/achats" class="nav-link <?= (isset($currentPage) && $currentPage === 'achats') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-cart-check-fill"></i>
                  <p>Achats</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/recap" class="nav-link <?= (isset($currentPage) && $currentPage === 'recap') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-clipboard-data"></i>
                  <p>Récapitulation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/dispatch/besoinsParVille" class="nav-link <?= (isset($currentPage) && $currentPage === 'dispatchVilles') ? 'active' : '' ?>">
                  <i class="nav-icon bi bi-geo-fill"></i>
                  <p>Dispatch Villes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/dispatch/" class="nav-link <?= (isset($currentPage) && $currentPage === 'dispatch') ? 'active' : '' ?>">
                  <i class="bi bi-list-check nav-icon"></i>
                  <p>Dispatch Manager</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->