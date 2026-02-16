      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Back-office Donation</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2024-2026&nbsp;
          <a href="<?= BASE_URL ?>/" class="text-decoration-none">BNGRC</a>.
        </strong>
        Tous droits réservés.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Stylesheets-->
    <!--begin::Preload AdminLTE-->
    <link rel="preload" href="<?= BASE_URL ?>/assets/css/adminlte.css" as="style" />
    <!--end::Preload AdminLTE-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/source-sans-3.css" media="print" onload="this.media = 'all'" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/overlayscrollbars.min.css" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.min.css" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::ApexCharts CSS-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/apexcharts.css" />
    <!--end::ApexCharts CSS-->
    <!--end::Stylesheets-->

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="<?= BASE_URL ?>/assets/js/overlayscrollbars.browser.es6.min.js"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="<?= BASE_URL ?>/assets/js/popper.min.js"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.min.js"></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="<?= BASE_URL ?>/assets/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        const isMobile = window.innerWidth <= 992;
        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- apexcharts -->
    <script src="<?= BASE_URL ?>/assets/js/apexcharts.min.js"></script>
  </body>
  <!--end::Body-->
</html>
