 const ctx = document.getElementById('myChart').getContext('2d');

    const chartData = {
      labels: ['Contas', 'Investimentos', 'Alimentação', 'Lazer'],
      datasets: [{
        label: 'Vendas (%)',
        data: [30, 25, 20, 25],
        backgroundColor: [
          'rgba(75, 192, 192, 0.7)',
          'rgba(255, 159, 64, 0.7)',
          'rgba(255, 205, 86, 0.7)',
          'rgba(153, 102, 255, 0.7)'
        ],
        borderColor: [
          'rgba(75, 192, 192, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(255, 205, 86, 1)',
          'rgba(153, 102, 255, 1)'
        ],
        borderWidth: 1
      }]
    };

    let chartOptions = {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Vendas por Categoria'
        }
      }
    };

    let currentChart = new Chart(ctx, {
      type: 'doughnut',
      data: chartData,
      options: chartOptions
    });

    function updateChartType(newType) {
      currentChart.destroy(); 
      currentChart = new Chart(ctx, {
        type: newType,
        data: chartData,
        options: chartOptions
      });
    }

    document.querySelectorAll('input[name="chartType"]').forEach(radio => {
      radio.addEventListener('change', (event) => {
        updateChartType(event.target.value);
      });
    });