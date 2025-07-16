 const ctx = document.getElementById('myChart').getContext('2d');

// Usa dados reais se disponíveis
const chartData = {
  labels: window.graficoCategorias ? window.graficoCategorias.labels : ['Contas', 'Investimentos', 'Alimentação', 'Lazer'],
  datasets: [{
    label: 'Despesas (R$)',
    data: window.graficoCategorias ? window.graficoCategorias.data : [30, 25, 20, 25],
    backgroundColor: [
      'rgba(75, 192, 192, 0.7)',
      'rgba(255, 159, 64, 0.7)',
      'rgba(255, 205, 86, 0.7)',
      'rgba(153, 102, 255, 0.7)',
      'rgba(231, 76, 60, 0.7)',
      'rgba(46, 204, 113, 0.7)',
      'rgba(59, 130, 246, 0.7)',
      'rgba(255, 205, 86, 0.7)',
      'rgba(153, 102, 255, 0.7)'
    ],
    borderColor: [
      'rgba(75, 192, 192, 1)',
      'rgba(255, 159, 64, 1)',
      'rgba(255, 205, 86, 1)',
      'rgba(153, 102, 255, 1)',
      'rgba(231, 76, 60, 1)',
      'rgba(46, 204, 113, 1)',
      'rgba(59, 130, 246, 1)',
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
      text: 'Despesas por Categoria'
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