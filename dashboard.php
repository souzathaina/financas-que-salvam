<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./index.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // Busca as despesas do usuário
    $sql = 'SELECT d.*, c.nome as categoria_nome 
            FROM despesas d 
            LEFT JOIN categorias c ON d.categoria = c.id 
            WHERE d.usuario_id = :usuario_id 
            ORDER BY d.data DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $despesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcula o total gasto
    $sqlTotal = 'SELECT SUM(valor) as total FROM despesas WHERE usuario_id = :usuario_id';
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute([':usuario_id' => $usuario_id]);
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    // Busca dados do usuário (incluindo salário)
    $sqlUsuario = 'SELECT nome, email, salario FROM usuarios WHERE id = :usuario_id';
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    // Calcula estatísticas por categoria
    $sqlCategorias = 'SELECT c.nome, SUM(d.valor) as total_categoria
                      FROM despesas d 
                      LEFT JOIN categorias c ON d.categoria = c.id 
                      WHERE d.usuario_id = :usuario_id 
                      GROUP BY c.id, c.nome 
                      ORDER BY total_categoria DESC';
    $stmtCategorias = $pdo->prepare($sqlCategorias);
    $stmtCategorias->execute([':usuario_id' => $usuario_id]);
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao carregar dashboard: " . $e->getMessage());
    $despesas = [];
    $total = ['total' => 0];
    $usuario = ['nome' => 'Usuário', 'email' => '', 'salario' => 0];
    $categorias = [];
}

// Calcula percentual gasto do salário
$salario = $usuario['salario'] ?? 0;
$totalGasto = $total['total'] ?? 0;
$percentualGasto = $salario > 0 ? ($totalGasto / $salario) * 100 : 0;
$saldoRestante = $salario - $totalGasto;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/index.css">
  <script src="https://kit.fontawesome.com/a2d9d3f09f.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .dashboard-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .graficos-section {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      margin: 40px 0;
    }
    
    .grafico-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .grafico-card h3 {
      color: #1E90FF;
      margin-bottom: 20px;
      text-align: center;
    }
    
    .chart-container {
      position: relative;
      height: 300px;
    }
    
    .acoes-dashboard {
      display: flex;
      gap: 15px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }
    
    .btn-dashboard {
      background-color: #3B82F6;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .btn-dashboard:hover {
      background-color: #2563EB;
    }
    
    .btn-dashboard.verde {
      background-color: #2ecc71;
    }
    
    .btn-dashboard.verde:hover {
      background-color: #27ae60;
    }
    
    .btn-dashboard.vermelho {
      background-color: #e74c3c;
    }
    
    .btn-dashboard.vermelho:hover {
      background-color: #c0392b;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    
    .stat-card h4 {
      color: #666;
      font-size: 0.9rem;
      margin-bottom: 10px;
    }
    
    .stat-value {
      font-size: 1.8rem;
      font-weight: bold;
      color: #1E90FF;
    }
    
    .stat-card.destaque .stat-value {
      color: #2ecc71;
    }
    
    .stat-card.alerta .stat-value {
      color: #e74c3c;
    }
    
    .welcome-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      border-radius: 12px;
      margin-bottom: 30px;
    }
    
    .welcome-section h1 {
      color: white;
      margin-bottom: 10px;
    }
    
    .welcome-section p {
      color: rgba(255,255,255,0.9);
      margin: 0;
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <h1>Finanças que Salvam</h1>
      <div class="acoes-header">
        <span>Olá, <?php echo htmlspecialchars($usuario['nome']); ?>!</span>
        <a href="index.php" class="link-entrar">Sair</a>
      </div>
    </div>
  </header>

  <div class="dashboard-container">
    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1' && isset($_GET['msg']) && $_GET['msg'] == 'salario_atualizado'): ?>
      <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <i class="fas fa-check-circle"></i> Salário atualizado com sucesso!
      </div>
    <?php endif; ?>
    
    <!-- SEÇÃO DE BOAS-VINDAS -->
    <section class="welcome-section">
      <h1>Bem-vindo ao seu Dashboard Financeiro!</h1>
      <p>Monitore seus gastos e mantenha suas finanças em ordem.</p>
    </section>

    <!-- AÇÕES RÁPIDAS -->
    <section class="acoes-dashboard">
      <a href="cadastrar_despesas.php" class="btn-dashboard verde">
        <i class="fas fa-plus"></i>
        Nova Despesa
      </a>
      <a href="NovaCategoria.php" class="btn-dashboard">
        <i class="fas fa-tags"></i>
        Nova Categoria
      </a>
      <a href="configurar_salario.php" class="btn-dashboard">
        <i class="fas fa-cog"></i>
        Configurar Salário
      </a>
      <a href="graficos.php" class="btn-dashboard">
        <i class="fas fa-chart-pie"></i>
        Gráficos Detalhados
      </a>
    </section>

    <!-- ESTATÍSTICAS RÁPIDAS -->
    <section class="stats-grid">
      <div class="stat-card">
        <h4>Total Gasto</h4>
        <div class="stat-value">R$ <?php echo number_format($totalGasto, 2, ',', '.'); ?></div>
      </div>
      
      <div class="stat-card">
        <h4>Salário Mensal</h4>
        <div class="stat-value">R$ <?php echo number_format($salario, 2, ',', '.'); ?></div>
      </div>
      
      <div class="stat-card <?php echo $percentualGasto > 80 ? 'alerta' : 'destaque'; ?>">
        <h4>Percentual Gasto</h4>
        <div class="stat-value"><?php echo number_format($percentualGasto, 1); ?>%</div>
      </div>
      
      <div class="stat-card destaque">
        <h4>Saldo Restante</h4>
        <div class="stat-value">R$ <?php echo number_format($saldoRestante, 2, ',', '.'); ?></div>
      </div>
    </section>

    <!-- GRÁFICOS -->
    <section class="graficos-section">
      <div class="grafico-card">
        <h3>Despesas por Categoria</h3>
        <div class="chart-container">
          <canvas id="chartCategorias"></canvas>
        </div>
      </div>
      
      <div class="grafico-card">
        <h3>Gastos vs Salário</h3>
        <div class="chart-container">
          <canvas id="chartSalario"></canvas>
        </div>
      </div>
    </section>

    <!-- TABELA DE DESPESAS -->
    <section>
      <h2 style="margin-bottom: 20px; color: #1E90FF;">Minhas Despesas Recentes</h2>
      
      <?php if (empty($despesas)): ?>
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
          <i class="fas fa-receipt" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
          <h3 style="color: #666; margin-bottom: 10px;">Nenhuma despesa registrada</h3>
          <p style="color: #999;">Comece registrando sua primeira despesa!</p>
          <a href="cadastrar_despesas.php" class="btn-verde" style="margin-top: 20px;">Cadastrar Primeira Despesa</a>
        </div>
      <?php else: ?>
        <table class="tabela-despesas">
          <thead>
            <tr>
              <th>Data</th>
              <th>Categoria</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($despesas as $despesa): ?>
            <tr>
              <td><?php echo date('d/m/Y', strtotime($despesa['data'])); ?></td>
              <td><?php echo htmlspecialchars($despesa['categoria_nome'] ?? 'Sem categoria'); ?></td>
              <td><?php echo htmlspecialchars($despesa['descricao']); ?></td>
              <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
              <td>
                <button class="btn-editar" onclick="editarDespesa(<?php echo $despesa['id']; ?>)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn-excluir" onclick="deletarDespesa(<?php echo $despesa['id']; ?>)">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </div>

  <script>
    // Dados para os gráficos
    const categoriasData = {
      labels: <?php echo json_encode(array_column($categorias, 'nome')); ?>,
      datasets: [{
        label: 'Valor (R$)',
        data: <?php echo json_encode(array_column($categorias, 'total_categoria')); ?>,
        backgroundColor: [
          'rgba(59, 130, 246, 0.7)',
          'rgba(46, 204, 113, 0.7)',
          'rgba(255, 159, 64, 0.7)',
          'rgba(231, 76, 60, 0.7)',
          'rgba(153, 102, 255, 0.7)',
          'rgba(255, 205, 86, 0.7)'
        ],
        borderColor: [
          'rgba(59, 130, 246, 1)',
          'rgba(46, 204, 113, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(231, 76, 60, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 205, 86, 1)'
        ],
        borderWidth: 2
      }]
    };

    const salarioData = {
      labels: ['Gastos', 'Saldo Restante'],
      datasets: [{
        label: 'Valor (R$)',
        data: [<?php echo $totalGasto; ?>, <?php echo $saldoRestante; ?>],
        backgroundColor: [
          'rgba(231, 76, 60, 0.7)',
          'rgba(46, 204, 113, 0.7)'
        ],
        borderColor: [
          'rgba(231, 76, 60, 1)',
          'rgba(46, 204, 113, 1)'
        ],
        borderWidth: 2
      }]
    };

    // Configurações dos gráficos
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
        title: {
          display: false
        }
      }
    };

    // Criar gráfico de categorias
    const ctxCategorias = document.getElementById('chartCategorias').getContext('2d');
    new Chart(ctxCategorias, {
      type: 'doughnut',
      data: categoriasData,
      options: chartOptions
    });

    // Criar gráfico de salário
    const ctxSalario = document.getElementById('chartSalario').getContext('2d');
    new Chart(ctxSalario, {
      type: 'doughnut',
      data: salarioData,
      options: chartOptions
    });

    // Funções para ações
    function editarDespesa(id) {
      // Implementar edição
      alert('Funcionalidade de edição será implementada');
    }

    function deletarDespesa(id) {
      if (confirm('Tem certeza que deseja excluir esta despesa?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'DeletarDespesa.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>
</body>

</html>
