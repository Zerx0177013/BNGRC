<?php include __DIR__ . '/header.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Dashboard</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Donations reçues</h3>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <p class="d-flex flex-column">
                        <span class="fw-bold fs-5">820</span>
                        <span>Donations au fil du temps</span>
                      </p>
                      <p class="ms-auto d-flex flex-column text-end">
                        <span class="text-success"> <i class="bi bi-arrow-up"></i> 12.5% </span>
                        <span class="text-secondary">Depuis la semaine dernière</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                      <div id="visitors-chart"></div>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                      <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Cette semaine
                      </span>

                      <span> <i class="bi bi-square-fill text-secondary"></i> Semaine dernière </span>
                    </div>
                  </div>
                </div>
                <!-- /.card -->

                <div class="card mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title">Dons</h3>
                    <div class="card-tools">
                      <a href="javascript:void(0)" class="btn btn-tool btn-sm">
                        <i class="bi bi-download"></i>
                      </a>
                      <a href="javascript:void(0)" class="btn btn-tool btn-sm">
                        <i class="bi bi-list"></i>
                      </a>
                    </div>
                  </div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                      <thead>
                        <tr>
                          <th>Don</th>
                          <th>Qté</th>
                          <th>Unités</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Riz</td>
                          <td>500</td>
                          <td>Kg</td>
                        </tr>
                        <tr>
                          <td>Eau potable</td>
                          <td>1 200</td>
                          <td>Litres</td>
                        </tr>
                        <tr>
                          <td>Couvertures</td>
                          <td>350</td>
                          <td>Pièces</td>
                        </tr>
                        <tr>
                          <td>Médicaments</td>
                          <td>80</td>
                          <td>Cartons</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col-md-6 -->
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Dispatches</h3>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <p class="d-flex flex-column">
                        <span class="fw-bold fs-5">1 230</span>
                        <span>Dispatches au fil du temps</span>
                      </p>
                      <p class="ms-auto d-flex flex-column text-end">
                        <span class="text-success"> <i class="bi bi-arrow-up"></i> 33.1% </span>
                        <span class="text-secondary">Depuis l'année dernière</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                      <div id="sales-chart"></div>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                      <span class="me-2">
                        <i class="bi bi-square-fill text-primary"></i> Cette année
                      </span>

                      <span> <i class="bi bi-square-fill text-secondary"></i> Année dernière </span>
                    </div>
                  </div>
                </div>
                <!-- /.card -->

                <div class="card">
                  <div class="card-header border-0">
                    <h3 class="card-title">Aperçu des opérations</h3>
                  </div>
                  <div class="card-body">
                    <div
                      class="d-flex justify-content-between align-items-center border-bottom mb-3"
                    >
                      <p class="text-success fs-2">
                        <i class="bi bi-gift-fill"></i>
                      </p>
                      <p class="d-flex flex-column text-end">
                        <span class="fw-bold">
                          <i class="bi bi-graph-up-arrow text-success"></i> 12%
                        </span>
                        <span class="text-secondary">TAUX DE DONS</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
                    <div
                      class="d-flex justify-content-between align-items-center border-bottom mb-3"
                    >
                      <p class="text-info fs-2">
                        <i class="bi bi-truck"></i>
                      </p>
                      <p class="d-flex flex-column text-end">
                        <span class="fw-bold">
                          <i class="bi bi-graph-up-arrow text-info"></i> 0.8%
                        </span>
                        <span class="text-secondary">TAUX DE DISPATCH</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                      <p class="text-warning fs-2">
                        <i class="bi bi-people-fill"></i>
                      </p>
                      <p class="d-flex flex-column text-end">
                        <span class="fw-bold">
                          <i class="bi bi-graph-up-arrow text-warning"></i>
                          5%
                        </span>
                        <span class="text-secondary">NOUVEAUX DONATEURS</span>
                      </p>
                    </div>
                    <!-- /.d-flex -->
                  </div>
                </div>
              </div>
              <!-- /.col-md-6 -->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
<?php include __DIR__ . '/footer.php'; ?>

    <script>
      const visitors_chart_options = {
        series: [
          {
            name: 'Dons - 2026',
            data: [100, 120, 170, 167, 180, 177, 160],
          },
          {
            name: 'Dons - 2025',
            data: [60, 80, 70, 67, 80, 77, 100],
          },
        ],
        chart: {
          height: 200,
          type: 'line',
          toolbar: {
            show: false,
          },
        },
        colors: ['#0d6efd', '#adb5bd'],
        stroke: {
          curve: 'smooth',
        },
        grid: {
          borderColor: '#e7e7e7',
          row: {
            colors: ['#f3f3f3', 'transparent'],
            opacity: 0.5,
          },
        },
        legend: {
          show: false,
        },
        markers: {
          size: 1,
        },
        xaxis: {
          categories: ['22th', '23th', '24th', '25th', '26th', '27th', '28th'],
        },
      };

      const visitors_chart = new ApexCharts(
        document.querySelector('#visitors-chart'),
        visitors_chart_options,
      );
      visitors_chart.render();

      const sales_chart_options = {
        series: [
          {
            name: 'Alimentaire',
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
          },
          {
            name: 'Médical',
            data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
          },
          {
            name: 'Matériel',
            data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
          },
        ],
        chart: {
          type: 'bar',
          height: 200,
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997', '#ffc107'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent'],
        },
        xaxis: {
          categories: ['Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct'],
        },
        fill: {
          opacity: 1,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + ' unités';
            },
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#sales-chart'),
        sales_chart_options,
      );
      sales_chart.render();
    </script>
