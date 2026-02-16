<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>BNGRC | Dashboard</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="BNGRC | Dashboard" />
    <meta name="author" content="BNGRC" />
    <meta
      name="description"
      content="BNGRC - Back-office de gestion des donations et dispatches."
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <meta name="supported-color-schemes" content="light dark" />
    <!--end::Accessibility Features-->
  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="<?= BASE_URL ?>/" class="nav-link">Accueil</a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">3 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item">
                  <i class="bi bi-gift me-2"></i> 2 nouveaux dons
                  <span class="float-end text-secondary fs-7">3 min</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item">
                  <i class="bi bi-truck me-2"></i> 1 dispatch en cours
                  <span class="float-end text-secondary fs-7">1 heure</span>
                </a>
                <div class="dropdown-divider"></div>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <p>
                    Administrateur BNGRC
                    <small>Back-office Donation</small>
                  </p>
                </li>
                <li class="user-footer">
                  <a href="javascript:void(0)" class="btn btn-outline-danger float-end">Déconnexion</a>
                </li>
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <?php include __DIR__ . '/sidebar.php'; ?>
