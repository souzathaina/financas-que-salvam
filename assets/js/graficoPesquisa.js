// Script independente para o gráfico Gastos vs Salário (Pesquisa)
let chartPesquisa = null;

async function buscarDadosGrafico(formData) {
  const params = new URLSearchParams(formData).toString();
  const response = await fetch('dadosGrafico.php?' + params);
  if (!response.ok) throw new Error('Erro ao buscar dados do gráfico');
  return await response.json();
}

function atualizarGraficoPesquisa(dados) {
  const ctx = document.getElementById('chartPesquisa').getContext('2d');
  if (chartPesquisa) chartPesquisa.destroy();
  chartPesquisa = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Salário', 'Gastos'],
      datasets: [{
        label: 'R$ (Reais)',
        data: [dados.salario, dados.gasto],
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
}

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('form-grafico-pesquisa');
  if (!form) return;
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    try {
      const dados = await buscarDadosGrafico(formData);
      atualizarGraficoPesquisa(dados);
    } catch (err) {
      alert('Erro ao atualizar gráfico: ' + err.message);
    }
  });
  // Inicializa o gráfico vazio
  atualizarGraficoPesquisa({salario: 0, gasto: 0});
}); 