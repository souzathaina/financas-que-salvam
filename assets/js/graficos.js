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
    borderWidth: 2
  }]
};

let chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        padding: 15,
        usePointStyle: true,
        font: {
          size: 12
        }
      }
    },
    title: {
      display: true,
      text: 'Despesas por Categoria',
      font: {
        size: 16,
        weight: 'bold'
      },
      padding: {
        top: 10,
        bottom: 20
      }
    }
  },
  layout: {
    padding: {
      top: 20,
      bottom: 20
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

// Gráfico Gastos vs Salário
const ctxSalario = document.getElementById('chartSalario').getContext('2d');
const dadosSalario = window.graficoSalario || { salario: 0, gasto: 0 };

const chartSalario = new Chart(ctxSalario, {
  type: 'bar',
  data: {
    labels: ['Salário', 'Gastos'],
    datasets: [{
      label: 'R$ (Reais)',
      data: [dadosSalario.salario, dadosSalario.gasto],
      backgroundColor: [
        'rgba(46, 204, 113, 0.7)', // Salário
        'rgba(231, 76, 60, 0.7)'   // Gastos
      ],
      borderColor: [
        'rgba(46, 204, 113, 1)',
        'rgba(231, 76, 60, 1)'
      ],
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Gastos vs Salário',
        font: { size: 16, weight: 'bold' },
        padding: { top: 10, bottom: 20 }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
          }
        }
      }
    }
  }
});