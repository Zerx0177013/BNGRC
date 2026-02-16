/**
 * Dashboard - JavaScript
 * Charts ApexCharts pour le dashboard (dons par jour et dispatches par catégorie)
 */
document.addEventListener('DOMContentLoaded', function () {
  // Les données sont injectées via des variables globales: donsParJour et dispatchParCategorie

  // --- Line chart : Dons par jour ---
  if (typeof donsParJour !== 'undefined' && document.getElementById('visitors-chart')) {
    const visitors_chart_options = {
      series: [
        {
          name: 'Quantité donnée',
          data: donsParJour.map(d => parseFloat(d.total)),
        },
      ],
      chart: {
        height: 200,
        type: 'line',
        toolbar: {
          show: false,
        },
      },
      colors: ['#0d6efd'],
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
        size: 3,
      },
      xaxis: {
        categories: donsParJour.map(d => d.jour),
      },
    };

    const visitors_chart = new ApexCharts(
      document.querySelector('#visitors-chart'),
      visitors_chart_options,
    );
    visitors_chart.render();
  }

  // --- Bar chart : Dispatches par catégorie ---
  if (typeof dispatchParCategorie !== 'undefined' && document.getElementById('sales-chart')) {
    const catColors = {
      'Nature': '#0d6efd',
      'Matériaux': '#ffc107',
      'Argent': '#198754',
    };

    // Ajuster l'échelle : diviser Argent par 1 000
    const scaledData = dispatchParCategorie.map(d => {
      const val = parseFloat(d.total);
      return d.nom_categorie === 'Argent' ? val / 1000 : val;
    });
    const scaledLabels = dispatchParCategorie.map(d => {
      return d.nom_categorie === 'Argent' ? d.nom_categorie + ' (×1 000)' : d.nom_categorie;
    });

    const sales_chart_options = {
      series: [
        {
          name: 'Quantité dispatchée',
          data: scaledData,
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
          distributed: true,
        },
      },
      legend: {
        show: false,
      },
      colors: dispatchParCategorie.map(d => catColors[d.nom_categorie] || '#adb5bd'),
      dataLabels: {
        enabled: false,
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent'],
      },
      xaxis: {
        categories: scaledLabels,
      },
      fill: {
        opacity: 1,
      },
      tooltip: {
        y: {
          formatter: function (val, { dataPointIndex }) {
            const cat = dispatchParCategorie[dataPointIndex].nom_categorie;
            if (cat === 'Argent') {
              return (val * 1000).toLocaleString('fr-FR') + ' Ar';
            }
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
  }
});
