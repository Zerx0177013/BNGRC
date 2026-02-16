      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="<?= BASE_URL ?>/" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="<?= BASE_URL ?>/assets/img/AdminLTELogo.png"
              alt="BNGRC Logo"
              class="brand-image opacity-75 shadow"
            />
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
              id="navigation"
            >
              <li class="nav-header">MENU PRINCIPAL</li>
              <li class="nav-item">
                <a href="<?= BASE_URL ?>/" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-gift-fill"></i>
                  <p>Dons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-truck"></i>
                  <p>Dispatch</p>
                </a>
              </li>

              <li class="nav-header">DONATION</li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-heart-fill"></i>
                  <p>Liste des dons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-plus-circle-fill"></i>
                  <p>Nouveau don</p>
                </a>
              </li>

              <li class="nav-header">CATEGORIES</li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-tags-fill"></i>
                  <p>
                    Catégories
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Alimentaire</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Vestimentaire</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Médical</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Matériel</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-header">DOCUMENTATION</li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-file-earmark-text-fill"></i>
                  <p>Rapports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-journal-text"></i>
                  <p>Historique</p>
                </a>
              </li>

              <li class="nav-header">CONTACTS</li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-people-fill"></i>
                  <p>Donateurs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon bi bi-building"></i>
                  <p>Organisations</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
